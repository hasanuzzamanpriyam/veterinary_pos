<?php

namespace App\Livewire\CashMaintenance;

use App\Models\CashManager;
use App\Models\CashTransactions;
use App\Models\CustomerLedger;
use App\Models\Expense;
use App\Models\SupplierLedger;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class CashEdit extends Component
{
    public $id;
    public $date;
    public Collection $collections_data;
    public Collection $payments_data;
    public Collection $expenses_data;
    public $total_summary_rows = [
        'prev_balance' => 0,
        'collection' => 0,
        'payment' => 0,
        'expense' => 0,
        'home_cash' => 0,
        'short_cash' => 0
    ];
    public $allData = [];

    public $selectedCollectionIds = [];
    public $selectedPaymentIds = [];
    public $selectedExpenseIds = [];



    public function mount($id)
    {
        $this->id = $id;
        $this->collections_data = collect();
        $this->payments_data = collect();
        $this->expenses_data = collect();

        $cash_data = CashManager::where('id', $id)->first();
        if($cash_data){
            $this->date = Carbon::createFromFormat('Y-m-d H:i:s', $cash_data->date);
            $this->total_summary_rows['prev_balance'] = $cash_data->prev_balance;
            $this->total_summary_rows['collection'] = $cash_data->collection;
            $this->total_summary_rows['payment'] = $cash_data->payment;
            $this->total_summary_rows['expense'] = $cash_data->expense;
            $this->total_summary_rows['home_cash'] = $cash_data->home_cash;
            $this->total_summary_rows['short_cash'] = $cash_data->short_cash;

            $startOfDay = $this->date->copy()->startOfDay();
            $endOfDay = $this->date->copy()->endOfDay();
            $this->collections_data = CustomerLedger::whereBetween('date', [$startOfDay, $endOfDay])
                ->where('payment', '>', 0)
                ->get();
            $this->payments_data = SupplierLedger::whereBetween('date', [$startOfDay, $endOfDay])
                ->where('payment', '>', 0)
                ->get();
            $this->expenses_data = Expense::whereBetween('updated_at', [$startOfDay, $endOfDay])
                ->with('employee')
                ->where('amount', '>', 0)
                ->get();
        }

        $cash_trnxs = CashTransactions::where('trnx_id', $id)->get();

        if($cash_trnxs){
            $this->allData['collection'] = collect();
            $this->allData['collection_editable'] = collect();
            $this->allData['payment'] = collect();
            $this->allData['payment_editable'] = collect();
            $this->allData['expense'] = collect();
            $this->allData['expense_editable'] = collect();
            $this->allData['short_cash'] = collect();
            $this->allData['home_cash'] = collect();

            foreach ($cash_trnxs as $value) {
                $type = $value->type;
                $this->allData[$type]->push([
                    'type' => $type,
                    'name' =>$value->name,
                    'address' =>$value->address,
                    'mobile' =>$value->mobile,
                    'note' =>$value->note,
                    'amount' => $value->amount
                ]);
            }
        }

    }

    public function createSession()
    {
        session()->put('cash_edit_data',  [
            'id' => $this->id,
            'date' => $this->date,
            'allData' => $this->allData,
            'summary' => $this->total_summary_rows,
        ]);
        return redirect()->route('cash_maintenance.edit-checkout');
    }

    public function removeFromItem($type, $index)
    {
        unset($this->allData[$type][$index]);
        $this->generateSummary();
    }

    public function addRow($type)
    {
        $this->allData[$type]->push([
            'type' => $type,
            'name' =>'',
            'address' =>'',
            'mobile' =>'',
            'note' =>'',
            'amount' => 0
        ]);
    }

    public function generateSummary()
    {
        $this->total_summary_rows = [
            'prev_balance' => $this->total_summary_rows['prev_balance'],
            'collection' => 0,
            'payment' => 0,
            'expense' => 0,
            'home_cash' => 0,
            'short_cash' => 0
        ];
        foreach($this->allData as $type => $data){
            if($type == 'collection' || $type == 'collection_editable'){
                foreach($data as $item){
                    $this->total_summary_rows['collection'] = $this->total_summary_rows['collection'] ?? 0;
                    $this->total_summary_rows['collection'] += isset($item['amount']) && $item['amount'] ? floatval($item['amount']) : 0;
                }

            }
            if($type == 'payment' || $type == 'payment_editable'){
                foreach($data as $item){
                    $this->total_summary_rows['payment'] = $this->total_summary_rows['payment'] ?? 0;
                    $this->total_summary_rows['payment'] += isset($item['amount']) && $item['amount'] ? floatval($item['amount']) : 0;
                }
            }
            if($type == 'expense' || $type == 'expense_editable'){
                foreach($data as $item){
                    $this->total_summary_rows['expense'] = $this->total_summary_rows['expense'] ?? 0;
                    $this->total_summary_rows['expense'] += isset($item['amount']) && $item['amount'] ? floatval($item['amount']) : 0;
                }
            }
            if($type == 'home_cash'){
                foreach($data as $item){
                    $this->total_summary_rows['home_cash'] = isset($item['amount']) && $item['amount'] ? floatval($item['amount']) : 0;
                }

            }
            if($type == 'short_cash'){
                foreach($data as $item){
                    $this->total_summary_rows['short_cash'] = isset($item['amount']) && $item['amount'] ? floatval($item['amount']) : 0;
                }

            }
        }
    }

    public function updateSummary()
    {
        $this->generateSummary();
    }

    #[On('update-collections')]
    public function updateCollections($selectedIds)
    {
        if ($selectedIds) {
            $this->selectedCollectionIds = $selectedIds;
            $collections = CustomerLedger::whereIn('id', $selectedIds)->with('customer')->get();

            if($collections && $collections->count() > 0){
                $temp_collection = collect();
                foreach ($collections as $key => $value) {
                    $temp_collection->push([
                        'type' => 'collection',
                        'name' =>$value->customer->name,
                        'address' =>$value->customer->address,
                        'mobile' =>$value->customer->mobile,
                        'note' =>'',
                        'amount' => $value->payment
                    ]);
                }
                $this->allData['collection'] = collect();
                foreach($temp_collection as $key => $value){
                    $this->allData['collection']->push($value);
                }
            }
            $this->generateSummary();

        }
    }
    #[On('update-payments')]
    public function updatePayments($selectedIds)
    {
        if ($selectedIds) {
            $this->selectedPaymentIds = $selectedIds;
            $payments = SupplierLedger::whereIn('id', $selectedIds)->with('supplier')->get();

            if($payments && $payments->count() > 0){
                $temp_payment = collect();
                foreach ($payments as $key => $value) {
                    $temp_payment->push([
                        'type' => 'payment',
                        'name' =>$value->supplier->company_name,
                        'address' =>$value->supplier->address,
                        'mobile' =>$value->supplier->mobile,
                        'note' =>'',
                        'amount' => $value->payment
                    ]);
                }
                $this->allData['payment'] = collect();
                foreach($temp_payment as $key => $value){
                    $this->allData['payment']->push($value);
                }
            }

            $this->generateSummary();
        }
    }
    #[On('update-expenses')]
    public function updateExpenses($selectedIds)
    {
        if ($selectedIds) {
            $this->selectedExpenseIds = $selectedIds;
            $expenses = Expense::whereIn('id', $selectedIds)->with('employee')->get();

            if($expenses && $expenses->count() > 0){
                $temp_expense = collect();
                foreach ($expenses as $key => $value) {
                    $title = $value->expense_type == 'salary_expense' ? 'Salary' : $value->expense_type;
                    $note = $value->expense_type == 'salary_expense' ? $value->employee->name : $value->purpose;
                    $temp_expense->push([
                        'type' => 'expense',
                        'name' => $title,
                        'address' => '',
                        'mobile' => '',
                        'note' => $note,
                        'amount' => $value->amount
                    ]);
                }
                $this->allData['expense'] = collect();
                foreach($temp_expense as $key => $value){
                    $this->allData['expense']->push($value);
                }
            }

            $this->generateSummary();
        }
    }

    public function render()
    {
        return view('livewire.cash-maintenance.cash-edit', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
