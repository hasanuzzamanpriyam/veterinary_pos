<?php

namespace App\Livewire\Quotation;

use Livewire\Component;
use App\Models\Product;
use App\Models\customer;
use App\Models\Quotation;

class Index extends Component
{

    public $date;
    public $id;
    public $customer_id;
    public $customer_name;
    public $address;
    public $mobile;
    public $product_id;
    public $product_name;
    public $product_quantity;
    public $brand_id;
    public $brand_name;
    public $group_id;
    public $group_name;
    public $remarks;

    protected $listeners = ['deleteConfirmed'=>'deleteQuotation'];


    public function rules()
    {
        return
        [
            'date' => ['required'],
            'customer_name' => ['required'],
            'address' => ['nullable'],
            'mobile' => ['nullable'],
            'product_name' => ['nullable'],
            'product_quantity' => ['nullable'],
            'brand_name' => ['nullable'],
            'group_name' => ['nullable'],
            'remarks' => ['nullable'],

        ];

    }

    public function mount()
    {
        // Set the initial date when the component is mounted
        $this->date = now()->toDateString();

    }

    public function resetInput()
    {

        $this->reset(['customer_name','address','mobile','product_name','product_quantity','brand_name','group_name','remarks']);
        return redirect()->route('quotation.create');
    }

    public function closeModal()
    {

        $this->resetInput();
    }

    public function openModal()
    {
        $this->resetInput();
    }



    // search customer
    public function searchCustomer($id)
    {

        $customer = customer::where('id',$id)->first();

        $this->customer_id =  $customer->id;
        $this->customer_name =  $customer->name;
        $this->address =  $customer->address;
        $this->mobile =  $customer->mobile;



    }

     // search product
     public function searchProduct($id)
     {

         $product = Product::where('id',$id)->first();

         $this->product_id =  $product->id;
         $this->product_name =  $product->name;
         $this->product_quantity =  $product->opening_stock;
         $this->brand_id =  $product->brand_id;
         $this->brand_name =  $product->brand->name;
         $this->group_id =  $product->group_id;
         $this->group_name =  $product->productGroup->name;

     }

    public function storeQuotation()
    {
        $validateData = $this->validate();

        //dd( $validateData);

        Quotation::insert([
            'customer_id'=>$this->customer_id,
            'product_id'=> $this->product_id,
            'brand_id'=>$this->brand_id,
            'group_id'=> $this->group_id,
            'date'=>$validateData['date'],
            'quantity'=>$validateData['product_quantity'],
            'remarks'=>$validateData['remarks'],

        ]);


        $notification=array('msg' => 'Customer Quotation Successfully Inserted!', 'alert-type' => 'success');
        return redirect()->route('quotation.create')->with($notification);

    }

    public function edit($id)
    {

        $quotation = Quotation::where('id',$id)->first();
        $this->id =  $quotation->id;
        $this->product_id =  $quotation->product_id;
        $this->product_name =  $quotation->product->name;
        $this->product_quantity =  $quotation->quantity;
        $this->brand_id =  $quotation->brand_id;
        $this->brand_name =  $quotation->brand->name;
        $this->group_id =  $quotation->group_id;
        $this->group_name =  $quotation->productGroup->name;
        $this->customer_id =  $quotation->customer_id;
        $this->customer_name =  $quotation->customer->name;
        $this->address =  $quotation->customer->address;
        $this->mobile =  $quotation->customer->mobile;
        $this->remarks =  $quotation->remarks;

    }

    public function updateQuotation()
    {
        $validateData = $this->validate();

       // dd($validateData);

        Quotation::where('id',$this->id)->update([
            'customer_id'=>$this->customer_id,
            'product_id'=> $this->product_id,
            'brand_id'=>$this->brand_id,
            'group_id'=> $this->group_id,
            'date'=>$validateData['date'],
            'quantity'=>$validateData['product_quantity'],
            'remarks'=>$validateData['remarks'],

        ]);

        $notification=array('msg' => 'Customer Quotation Successfully Updated!', 'alert-type' => 'info');
        return redirect()->route('quotation.create')->with($notification);
    }

    public function delete($id)
    {
        $this->id = $id;
        $this->dispatch('show-delete-message');
    }

    public function deleteQuotation()
    {

        $quotation = Quotation::where('id', $this->id)->first();
        $quotation->delete();
        $this->dispatch('deleted');
        $notification=array('msg' => 'Customer Quotation Successfully Deleted!', 'alert-type' => 'danger');
        return redirect()->route('quotation.create')->with($notification);
    }



    public function render()
    {
        $quotations = Quotation::orderBy('id','DESC')->get();
        $customers = customer::get();
        $products = Product::get();
        return view('livewire.quotation.index', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
