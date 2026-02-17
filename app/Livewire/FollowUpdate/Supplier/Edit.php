<?php

namespace App\Livewire\FollowUpdate\Supplier;

use App\Models\SupplierFollowUpdate;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $invoice;
    public $supplier_id;
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
        $this->invoice = \App\Models\SupplierFollowUpdate::find($id);

        // dd($id, $this->invoice );

        if(!$this->invoice){
            return redirect()->route('follow-update.supplier.view-all');
        }
        $this->supplier_id = $this->invoice->supplier_id;
        $this->company_name = $this->invoice->supplier->company_name;
        $this->address = $this->invoice->supplier->address;
        $this->mobile = $this->invoice->supplier->mobile;
        $this->balance = $this->invoice->supplier->balance;
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

    public function updateSupplierFollowUpdate()
    {
        $validated = $this->validate();

        $combinedDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($validated['date'])) . ' ' . date('H:i:s')));
        SupplierFollowUpdate::where('id', $this->invoice->id)->update([
            'supplier_id'=>$this->supplier_id,
            'previous_due'=>$this->balance,
            'payment'=>$this->payment,
            'prev_date'=>$this->prev_date,
            'next_date'=>Carbon::parse($combinedDateTime)->toDateTimeString(),
            'remarks'=>$this->remarks,
            'updated_at'=>now(),
        ]);

        $notification=array('msg' => 'Supplier Follow Update successfully updated!', 'alert-type' => 'success');
        return redirect()->route('supplier.follow.index')->with($notification);


    }

    public function render()
    {
        return view('livewire.follow-update.supplier.edit', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
