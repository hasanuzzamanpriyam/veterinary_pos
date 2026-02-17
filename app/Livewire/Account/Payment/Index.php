<?php

namespace App\Livewire\Account\Payment;

use App\Models\Bank;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\SupplierFollowUpdate;
use App\Models\SupplierLedger;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{

    public $date;
    public $full_date;
    public $supplier_id;
    public $supplier_name;
    public $address;
    public $mobile;
    public $payment_by;
    public $bank_title;
    public $bank_list;
    public $remarks;
    public $payment = 0;
    public $current_due = 0;
    public $balance = 0;


    public function rules()
    {
        return
            [
                'date' => ['required'],
                'balance' => ['nullable'],
                'payment_by' => ['nullable'],
                'bank_title' => ['nullable'],
                'remarks' => ['nullable'],
                'payment' => ['nullable'],
                'current_due' => ['required'],
            ];
    }

    // update due amount automatically
    public function duePayment($key, $val)
    {
        if ($key && $val > 0) {
            $this->$key = $val;
            if ($key == 'payment') {
                $this->payment = $val;
            }
        } else {
            $this->$key = 0;
            $this->payment = 0;
        }
    }

    public function mount()
    {
        $this->full_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime(now()))));
        $this->date = date('d-m-Y', strtotime($this->full_date));
    }

    public function updatedDate($date){
        $this->full_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($date))));
        $this->date = $date;
    }




    // search supplier
    public function searchSupplier($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        $this->supplier_id =  $supplier->id;
        $this->supplier_name =  $supplier->company_name;
        $this->address =  $supplier->address;
        $this->mobile =  $supplier->mobile;
        $this->balance =  $supplier->balance;
    }

    public function storePayment()
    {

        $validateData = $this->validate();
        $inv = DB::transaction(function() use ($validateData) {
            $final_balance = $validateData["current_due"];

            $invoice = SupplierLedger::insertGetId([
                'supplier_id' => $this->supplier_id,
                'type' => 'collection',
                'balance' => $final_balance,
                'payment' => $validateData['payment'] ?? 0,
                'payment_by' => $validateData['payment_by'],
                'bank_title' => $validateData['bank_title'],
                'vat' => 0,
                'carring' => 0,
                'other_charge' => 0,
                'price_discount' => 0,
                'total_price' => 0,
                'date' => $this->full_date,
                'type' => 'payment',
                'payment_remarks' => $validateData['remarks'],
                'created_at' => now(),
                'updated_at' => now()

            ]);

            $rowsAfterInsert = SupplierLedger::where('supplier_id', $this->supplier_id)
                ->where('date', '>', $this->full_date)
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $toatl_rows_remain = count($rowsAfterInsert);
                if($toatl_rows_remain > 0){
                    foreach ($rowsAfterInsert as $row) {
                        $total_price = 0;
                        $toatl_rows_remain--;
                        $total_price = $row->type == 'return' ? -$row->total_price : $row->total_price;
                        $line_total = $total_price - $row->price_discount - $row->vat - $row->carring - $row->other_charge - $row->payment;
                        $final_balance += $line_total;
                        $row->balance = $final_balance;
                        $row->save();
                    }
                }

            Supplier::where('id', $this->supplier_id)->update(['balance' => $final_balance]);
            Supplier::where('id', $this->supplier_id)->update([
                'company_name' =>  $this->supplier_name,
                'address' => $this->address,
                'mobile' => $this->mobile,
            ]);

            // Update customer follow update
            $record = SupplierFollowUpdate::where('supplier_id', $this->supplier_id)->first();
            $updatedId = 0;
            if ($record) {
                $record->update([
                    'paid_amount' => $validateData['payment'] ?? 0,
                    'payment_date' => $this->full_date,
                ]);

                $updatedId = $record->id;
            }

            if ($updatedId) {
                SupplierLedger::where('id', $invoice)->update([
                    'supplier_follow_updates_id' => $updatedId
                ]);
            }

            return $invoice;

        });

        $notification = array('msg' => 'Payment Successfully Inserted!', 'alert-type' => 'success');
        return redirect()->route('purchase.view', ['invoice'=> $inv, 'view' => 'payment'])->with($notification);
    }

    // payment search
    public function paymentSearch($value)
    {

        if ($value == 'Bank' || $value == 'RTGS' || $value == 'Transfer' || $value == 'Pay Order') {
            $this->bank_list = 1;
        } elseif ($value == 'Cheque') {
            $this->bank_title = "";
            $this->bank_list = 2;
        } else {
            $this->bank_list = 0;
        }
    }

    public function get_previous_balance($supplier_id, $date){
        session()->flash('balance');
        $data = SupplierLedger::where('supplier_id', $supplier_id)->where('date', '<=', $date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();

        return $data->balance ?? 0;
    }

    public function render()
    {
        if($this->supplier_id && $this->full_date){
            $this->balance = $this->get_previous_balance($this->supplier_id, $this->full_date);
        }
        $this->current_due = $this->balance - $this->payment;
        $banks = Bank::get();
        $suppliers = Supplier::get();
        return view('livewire.account.payment.index', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
