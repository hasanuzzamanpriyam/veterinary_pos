<?php

namespace App\Livewire\CashMaintenance;

use App\Models\CashManager;
use App\Models\CustomerLedger;
use App\Models\Expense;
use App\Models\SupplierLedger;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public $date;
    public Collection $collection;
    public Collection $collection_data;
    public array $selectedCollectionIds = [];

    public Collection $payment;
    public Collection $payment_data;
    public array $selectedPaymentIds = [];

    public Collection $expense;
    public Collection $expense_data;
    public array $selectedExpenseIds = [];
    public $previous_balance = 0;

    public $allData = [];

    public $rows = [];
    public $total = [];
    public $total_summary = [];
    public $total_summary_rows = [
        'collection' => 0,
        'payment' => 0,
        'expense' => 0,
        'home_cash' => 0,
        'short_cash' => 0
    ];

    public function mount()
    {
        $this->collection = collect();
        $this->collection_data = collect();
        $this->payment = collect();
        $this->payment_data = collect();
        $this->expense = collect();
        $this->expense_data = collect();
        if(session()->has('cash_data')){
            $cash_data = session()->get('cash_data');
            $this->date = $cash_data['date'];
            $this->previous_balance = $cash_data['prev_balance'] ?? 0;
            $this->allData = $cash_data['allData'];
            $this->total_summary_rows = $cash_data['summary'];
            $this->rows = $cash_data['rows'];
        }
    }

    public function createSession()
    {
        $existing = CashManager::whereDate('date', date('Y-m-d', strtotime($this->date)))->first();

        if(!$existing){

            session()->put('cash_data',  [
                'date' => $this->date,
                'prev_balance' => $this->previous_balance,
                'allData' => $this->allData,
                'summary' => $this->total_summary_rows,
                'rows' => $this->rows
            ]);
            return redirect()->route('cash_maintenance.checkout');

        }else{
            $this->dispatch('cash-already-exists');
        }

    }

    public function addRow($type)
    {
        $this->rows[] = $type == 'expense' ? ['name' => '', 'note' => '', 'amount' => '', 'type' => $type . '_editable'] : ['name' => '', 'address' => '', 'mobile' => '', 'amount' => '', 'type' => $type . '_editable'];
    }
    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // Reindex after unset
        $this->buildCollectionWithCustomData();
        $this->generateSummary();
    }
    public function addCustomAmountToSummary()
    {
        $this->buildCollectionWithCustomData();
        $this->generateSummary();
    }

    public function buildCollectionWithCustomData()
    {
        $this->total_summary_rows = [
            'collection' => 0,
            'payment' => 0,
            'expense' => 0,
            'home_cash' => 0,
            'short_cash' => 0
        ];
        $this->allData['collection_custom'] = collect();
        $this->allData['payment_custom'] = collect();
        $this->allData['expense_custom'] = collect();
        foreach($this->rows as $row){
            if('collection' == $row['type'] || 'collection_editable' == $row['type']){
                $this->allData['collection_custom']->push([
                    'type' => $row['type'],
                    'name' => $row['name'],
                    'address' => $row['address'],
                    'mobile' => $row['mobile'],
                    'amount' => $row['amount']
                ]);
            }
            if('payment' == $row['type'] || 'payment_editable' == $row['type']){
                $this->allData['payment_custom']->push([
                    'type' => $row['type'],
                    'name' => $row['name'],
                    'address' => $row['address'],
                    'mobile' => $row['mobile'],
                    'amount' => $row['amount']
                ]);
            }
            if('expense' == $row['type'] || 'expense_editable' == $row['type']){
                $this->allData['expense_custom']->push([
                    'type' => $row['type'],
                    'name' => $row['name'],
                    'note' => $row['note'],
                    'amount' => $row['amount']
                ]);
            }
        }
        // dump($this->rows, $this->allData);
    }

    public function addCashToCollection($type, $name, $amount)
    {
        $this->allData[$type] = collect();
        $this->allData[$type]->push([
            'type' => $type,
            'name' =>$name,
            'amount' => $amount
        ]);
        $this->generateSummary();

    }

    public function buildCollection($type)
    {
        $this->allData[$type] = collect();

        foreach($this->$type as $data){
            if('expense' == $type){
                $name = 'salary_expense' == $data->expense_type ? 'Salary' : $data->expense_type;
                $note = 'salary_expense' == $data->expense_type ? $data->employee->name : $data->purpose;
                $this->allData[$type]->push([
                    'id' => $data->id,
                    'type' => 'expense',
                    'name' => $name,
                    'note' => $note,
                    'amount' => $data->amount + $data->other_charge
                ]);
            }else{
                $this->allData[$type]->push([
                    'id' => $data->id,
                    'type' => $type,
                    'name' => 'collection' == $type ? $data->customer->name : $data->supplier->company_name,
                    'address' => 'collection' == $type ? $data->customer->address : $data->supplier->address,
                    'mobile' => 'collection' == $type ? $data->customer->mobile : $data->supplier->mobile,
                    'amount' => $data->payment
                ]);
            }
        }
    }

    public function generateSummary()
    {
        $this->total_summary_rows = [
            'collection' => 0,
            'payment' => 0,
            'expense' => 0,
            'home_cash' => 0,
            'short_cash' => 0
        ];
        foreach($this->allData as $type => $data){
            if($type == 'collection' || $type == 'collection_custom'){
                foreach($data as $item){
                    $this->total_summary_rows['collection'] = $this->total_summary_rows['collection'] ?? 0;
                    $this->total_summary_rows['collection'] += isset($item['amount']) && $item['amount'] ? floatval($item['amount']) : 0;
                }

            }
            if($type == 'payment' || $type == 'payment_custom'){
                foreach($data as $item){
                    $this->total_summary_rows['payment'] = $this->total_summary_rows['payment'] ?? 0;
                    $this->total_summary_rows['payment'] += isset($item['amount']) && $item['amount'] ? floatval($item['amount']) : 0;
                }
            }
            if($type == 'expense' || $type == 'expense_custom'){
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

    #[On('update-collections')]
    public function updateCollections($selectedIds)
    {
        if ($selectedIds) {
            $this->selectedCollectionIds = $selectedIds;
            $this->collection = CustomerLedger::whereIn('id', $selectedIds)->with('customer')->get();


            $this->buildCollection('collection');
            $this->generateSummary();

        }
    }
    #[On('update-payments')]
    public function updatePayments($selectedIds)
    {
        if ($selectedIds) {
            $this->selectedPaymentIds = $selectedIds;
            $this->payment = SupplierLedger::whereIn('id', $selectedIds)->with('supplier')->get();


            $this->buildCollection('payment');
            $this->generateSummary();
        }
    }
    #[On('update-expenses')]
    public function updateExpenses($selectedIds)
    {
        if ($selectedIds) {
            $this->selectedExpenseIds = $selectedIds;
            $this->expense = Expense::whereIn('id', $selectedIds)->with('employee')->get();

            $this->buildCollection('expense');
            $this->generateSummary();
        }
    }
    public function removeFromCollection($id)
    {
        $this->collection = $this->collection->reject(function ($item) use ($id) {
            return $item->id == $id;
        });
        $this->selectedCollectionIds = $this->collection->pluck('id')->toArray();

        $this->allData['collection'] = collect();
        foreach($this->collection as $data){
            $this->allData['collection']->push([
                'id' => $data->id,
                'type' => $data->type,
                'name' => $data->customer->name,
                'address' => $data->customer->address,
                'mobile' => $data->customer->mobile,
                'amount' => $data->payment
            ]);
        }

        $this->generateSummary();
    }
    public function removeFromPayment($id)
    {
        $this->payment = $this->payment->reject(function ($item) use ($id) {
            return $item->id == $id;
        });
        $this->selectedPaymentIds = $this->payment->pluck('id')->toArray();

        $this->allData['payment'] = collect();
        foreach($this->payment as $data){
            $this->allData['payment']->push([
                'id' => $data->id,
                'type' => $data->type,
                'name' => $data->supplier->company_name,
                'address' => $data->supplier->address,
                'mobile' => $data->supplier->mobile,
                'amount' => $data->payment
            ]);
        }
        $this->generateSummary();
    }
    public function removeFromExpense($id)
    {
        $this->expense = $this->expense->reject(function ($item) use ($id) {
            return $item->id == $id;
        });
        $this->selectedExpenseIds = $this->expense->pluck('id')->toArray();

        $this->allData['expense'] = collect();
        foreach($this->expense as $data){
            $this->allData['expense']->push([
                'id' => $data->id,
                'type' => 'expense',
                'name' => $data->expense_type,
                'note' => $data->purpose,
                'amount' => $data->amount + $data->other_charge
            ]);
        }
        $this->generateSummary();
    }


    public function render()
    {
        if ($this->date){
            $date = Carbon::createFromFormat('d-m-Y', $this->date);
            $dateCopy = $date->copy();
            $previousDay = $dateCopy->subDay();


            $latestItem = CashManager::whereDate('date', '<=', date('Y-m-d', strtotime($previousDay)))
                ->orderBy('updated_at', 'desc')
                ->first();
            if($latestItem){
                $this->previous_balance = $latestItem->dokan_cash;
            }else{
                $this->previous_balance = 0;
            }

            foreach ($this->total as $key => $value) {
                if (isset($this->total_summary[$key])) {
                    $this->total_summary[$key] += $value;
                } else {
                    $this->total_summary[$key] = $value;
                }
            }
            foreach ($this->total_summary_rows as $key => $value) {
                if (isset($this->total_summary[$key])) {
                    $this->total_summary[$key] += $value;
                } else {
                    $this->total_summary[$key] = $value;
                }
            }

            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();
            $this->collection_data = CustomerLedger::whereBetween('date', [$startOfDay, $endOfDay])
            ->where('payment', '>', 0)
            ->get();
            $this->payment_data = SupplierLedger::whereBetween('date', [$startOfDay, $endOfDay])
            ->where('payment', '>', 0)
            ->get();
            $this->expense_data = Expense::with('employee')
            ->whereDate('date', date('Y-m-d', strtotime($this->date)))
            ->where(function($query) {
                $query->where('amount', '>', 0)
                        ->orWhere('other_charge', '>', 0);
            })
            ->get();
        }
        return view('livewire.cash-maintenance.create', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
