<?php

namespace App\Livewire\FollowUpdate\Customer;

use App\Models\CustomerFollowUpdate;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $invoice;
    public $customer_id;
    public $customer_name;
    public $company_name;
    public $address;
    public $mobile;
    public $balance;
    public $prev_date;
    public $date;
    public $remarks;
    public $payment;

    public function mount($id)
    {
        $this->invoice = \App\Models\CustomerFollowUpdate::find($id);

        // dd($id, $this->invoice );

        if(!$this->invoice){
            return redirect()->route('follow-update.customer.view-all');
        }
        $this->customer_id = $this->invoice->customer_id;
        $this->customer_name = $this->invoice->customer->name;
        $this->company_name = $this->invoice->customer->company_name;
        $this->address = $this->invoice->customer->address;
        $this->mobile = $this->invoice->customer->mobile;
        $this->balance = $this->invoice->customer->balance;
        $this->prev_date = $this->invoice->next_date;
        $this->date = date('d-m-Y', strtotime($this->invoice->next_date));
        $this->remarks = $this->invoice->remarks;
        $this->payment = $this->invoice->payment;
    }

    public function rules()
    {
        return
        [
            'date' => ['required'],
            'customer_name' => ['required'],
            'address' => ['nullable'],
            'mobile' => ['nullable'],
            'remarks' => ['nullable'],
            'payment' => ['required'],
        ];

    }

    public function messages()
    {
        return [
            'customer_name.required' => 'Customer is required',
            'date.required' => 'Date is required',
            'payment.required' => 'Payment is required',
        ];
    }

    public function updateCustomerFollowUpdate()
    {
        $validated = $this->validate();

        $combinedDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($validated['date'])) . ' ' . date('H:i:s')));
        CustomerFollowUpdate::where('id', $this->invoice->id)->update([
            'customer_id'=>$this->customer_id,
            'previous_due'=>$this->balance,
            'payment'=>$this->payment,
            'prev_date'=>$this->prev_date,
            'next_date'=>Carbon::parse($combinedDateTime)->toDateTimeString(),
            'remarks'=>$this->remarks,
            'updated_at'=>now(),
        ]);

        $notification=array('msg' => 'Customer Follow Update successfully updated!', 'alert-type' => 'success');
        return redirect()->route('customer.follow.index')->with($notification);


    }
    public function render()
    {
        return view('livewire.follow-update.customer.edit', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
