<?php

namespace App\Livewire\Account\Collection;

use App\Models\Bank;
use App\Models\CustomerLedger;
use App\Models\customer;
use App\Models\CustomerFollowUpdate;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $date;
    public $full_date;
    public $customer_id;
    public $customer_name;
    public $address;
    public $mobile;
    public $invoice_no;
    public $payment_by;
    public $bank_title;
    public $bank_list;
    public $received_by;
    public $payment = 0;
    public $current_due = 0;
    public $balance = 0;
    public $amount = 0;

    public function rules()
    {
        return
            [
                'date' => ['required'],
                'balance' => ['nullable'],
                'current_due' => ['nullable'],
                'payment_by' => ['nullable'],
                'bank_title' => ['nullable'],
                'received_by' => ['nullable'],
                'payment' => ['nullable'],
            ];
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

    // search customer
    public function searchCustomer($id)
    {
        $customer = customer::where('id', $id)->first();
        $this->customer_id =  $customer->id;
        $this->customer_name =  $customer->name;
        $this->address =  $customer->address;
        $this->mobile =  $customer->mobile;
        $this->balance =  $customer->balance;
    }

    // update due amount automatically
    public function dueCollection($key, $val)
    {
        if ($key && $val > 0) {
            $this->$key = $val;
            if ($key == 'due_collection') {
                $this->payment = $val;
            }
        } else {
            $this->$key = 0;
            $this->payment = 0;
        }
    }

    public function storeCollection()
    {

        $validateData = $this->validate();
        $curr_due = 0;
        $current_pay = 0;
        $inv = DB::transaction(function() use ($validateData) {
            $final_balance = $validateData["current_due"];

            $invoice = CustomerLedger::insertGetId([
                'customer_id' => $this->customer_id,
                'type' => 'collection',
                'balance' => $final_balance ?? 0,
                'vat' => 0,
                'carring' => 0,
                'price_discount' => 0,
                'total_price' => 0,
                'other_charge' => 0,
                'payment' => $validateData['payment'] ?? 0,
                'payment_by' => $validateData['payment_by'],
                'bank_title' => $validateData['bank_title'],
                'date' => $this->full_date,
                'received_by' => $validateData['received_by'],
                'created_at' => now(),
                'updated_at' => now()

            ]);

            $rowsAfterInsert = CustomerLedger::where('customer_id', $this->customer_id)
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
                    $line_total = $total_price - $row->price_discount + $row->vat + $row->carring + $row->other_charge - $row->payment;
                    $final_balance += $line_total;
                    $row->balance = $final_balance;
                    $row->save();
                }
            }
            customer::where('id', $this->customer_id)->update([
                'balance' => $final_balance
            ]);

            // Update customer follow update
            $record = CustomerFollowUpdate::where('customer_id', $this->customer_id)->first();
            $updatedId = 0;
            if ($record) {
                $record->update([
                    'paid_amount' => $validateData['payment'] ?? 0,
                    'payment_date' => $this->full_date,
                    'invoice_no' => $invoice
                ]);

                $updatedId = $record->id;
            }

            if ($updatedId) {
                CustomerLedger::where('id', $invoice)->update([
                    'customer_follow_updates_id' => $updatedId
                ]);
            }


            return $invoice;

        });

        //Update customer info
        customer::where('id', $this->customer_id)->update([
            'name' => $this->customer_name,
            'address' => $this->address,
            'mobile' => $this->mobile,
        ]);

        $customer = CustomerLedger::orderBy('id', 'DESC')->first();
        $notification = array('msg' => 'Collection Successfully Inserted!', 'alert-type' => 'success');
        return redirect()->route('collection.view', ['id' => $inv], compact('customer'));
    }

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

    public function get_previous_balance($customer_id, $date){
        session()->flash('balance');
        $data = CustomerLedger::where('customer_id', $customer_id)->where('date', '<=', $date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        return $data->balance ?? 0;
    }

    public function render()
    {
        if($this->customer_id && $this->full_date){
            $this->balance = $this->get_previous_balance($this->customer_id, $this->full_date);
        }
        $this->current_due = $this->balance - $this->payment;
        $banks = Bank::get();
        $customers = customer::get();
        $gateways = PaymentGateway::all();
        return view('livewire.account.collection.index',  get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
