<?php

namespace App\Livewire\FollowUpdate\Customer;

use App\Models\customer;
use App\Models\CustomerFollowUpdate;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $date;
    public $customer_id;
    public $customer_name;
    public $address;
    public $mobile;
    public $balance;
    public $remarks;
    public $payment;


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

    // search customer
    public function searchCustomer($id)
    {
        $customer = customer::where('id',$id)->first();
        if($customer){
            $this->customer_id =  $customer->id;
            $this->customer_name =  $customer->name;
            $this->address =  $customer->address;
            $this->mobile =  $customer->mobile;
            $this->balance =  $customer->balance;
        }else{
            $this->customer_id =  '';
            $this->customer_name =  '';
            $this->address =  '';
            $this->mobile =  '';
            $this->balance =  '';
        }
    }

    public function storeCustomerFollowUpdate()
    {

        $validateData = $this->validate();
        $combinedDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($this->date)) . ' ' . date('H:i:s')));
        CustomerFollowUpdate::insert([
            'customer_id'=>$this->customer_id,
            'previous_due'=>$this->balance,
            'payment'=>$validateData['payment'],
            'next_date'=>Carbon::parse($combinedDateTime)->toDateTimeString(),
            'remarks'=>$validateData['remarks'],
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $notification=array('msg' => 'Customer Follow Update Successfully Inserted!', 'alert-type' => 'success');
        return redirect()->route('customer.follow.index')->with($notification);
    }

    public function render()
    {
        $customers = customer::get();
        return view('livewire.follow-update.customer.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
