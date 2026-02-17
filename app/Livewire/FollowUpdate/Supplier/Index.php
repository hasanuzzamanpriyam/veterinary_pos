<?php

namespace App\Livewire\FollowUpdate\Supplier;

use App\Models\Supplier;
use App\Models\SupplierFollowUpdate;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $date;
    public $supplier_id;
    public $company_name;
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
            'company_name' => ['required'],
            'address' => ['nullable'],
            'mobile' => ['nullable'],
            'remarks' => ['nullable'],
            'payment' => ['required'],
        ];

    }

    public function messages()
    {
        return [
            'company_name.required' => 'Supplier is required',
            'date.required' => 'Date is required',
            'payment.required' => 'Payment is required',
        ];
    }


    // search customer
    public function searchSupplier($id)
    {

        $supplier = Supplier::where('id',$id)->first();

        if($supplier){
            $this->supplier_id =  $supplier->id;
            $this->company_name =  $supplier->company_name;
            $this->address =  $supplier->address;
            $this->mobile =  $supplier->mobile;
            $this->balance =  $supplier->balance;
        }else{
            $this->supplier_id =  '';
            $this->company_name =  '';
            $this->address =  '';
            $this->mobile =  '';
            $this->balance =  '';
        }
    }

    public function storeSupplierFollowUpdate()
    {

        $validateData = $this->validate();
        $combinedDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($this->date)) . ' ' . date('H:i:s')));
        SupplierFollowUpdate::insert([
            'supplier_id'=>$this->supplier_id,
            'previous_due'=>$this->balance,
            'payment'=>$validateData['payment'],
            'next_date'=>Carbon::parse($combinedDateTime)->toDateTimeString(),
            'remarks'=>$validateData['remarks'],
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $notification=array('msg' => 'Supplier Follow Update Successfully Inserted!', 'alert-type' => 'success');
        return redirect()->route('supplier.follow.index')->with($notification);
    }

    public function render()
    {
        $suppliers = Supplier::get();

        return view('livewire.follow-update.supplier.index', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
