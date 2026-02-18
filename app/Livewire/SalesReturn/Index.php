<?php

namespace App\Livewire\SalesReturn;

use App\Models\Brand;
use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\ProductStore;
use App\Models\Store;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Index extends Component
{

    public $customer_id;
    public $date;
    public $return_date;
    public $full_return_date;
    public $product_store_id;
    public $customer_search;
    public $balance;
    public $customer_name;
    public $address;
    public $mobile;
    public $invoice_no;
    public $product_store_name;
    public $sales_invoice_no;
    public $stock_out;
    public $products;
    public $remarks;
    public $brand_id;
    public $invoice;


    public function rules()
    {
        return
            [
                'customer_id' => ['required'],
                'date' => ['required'],
                'return_date' => ['required'],
                'product_store_id' => ['nullable'],
                'sales_invoice_no' => ['required'],
                'remarks' => ['nullable'],
            ];
    }

    //Increment cart product
    public function updateQuantity($id, $invoice, $quantities)
    {
        $sales_data = CustomerTransactionDetails::where('transaction_id', $invoice)->where('product_id', $id)->first();

        foreach (Cart::instance('sales_return')->content() as $item) {

            if ($quantities <= $sales_data->quantity) {
                if ($item->id == $id) {
                    $item->qty = $quantities;
                    $item->options->stock = $quantities;
                }
            } else {
                if ($item->id == $id) {
                    $item->options->stock = 0;
                }
            }
        }
    }

    public function updatedReturnDate($date){
        $this->return_date = $date;
        $this->full_return_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($date))));
    }

    //Increment cart product
    public function updateDiscount($id, $discounts)
    {
        foreach (Cart::instance('sales_return')->content() as $item) {
            if ($item->id == $id) {
                $item->options->discount = $discounts;
            }
        }
    }

    //Increment cart product
    public function updatePrice($id, $update_price)
    {
        foreach (Cart::instance('sales_return')->content() as $item) {
            if ($item->id == $id) {
                $item->price = $update_price;
            }
        }
    }


    //remove product from cart
    public function itemRemove($rowId)
    {
        $cart = Cart::instance('sales_return')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            Cart::remove($rowId);
        }
    }

    public function invoiceSearch($invoice)
    {
        $this->invoice = $invoice;
    }

    // add product to sales cart
    public function sessionStore($id)
    {

        $products = ProductStore::where('product_id', $id)->first();

        $currentDay = date('Y-m-d', strtotime($this->date));

        if($currentDay){
            $sales = CustomerTransactionDetails::whereRaw("DATE(date) = ?", [$currentDay])->where('customer_id', $this->customer_id)->where('product_id', $id)->first();
        }

        if($this->invoice){
            $sales = CustomerTransactionDetails::where('transaction_id', $this->invoice)->where('product_id', $id)->first();
        }

        if($sales->quantity > 0){
            Cart::instance('sales_return')->add([
                'id' =>  $sales->product_id,
                'name' => $sales->product_name,
                'qty' => 1,
                'price' => $sales->unit_price ?? $sales->unit_price,
                'options' => [
                    'transaction_id' => $sales->id,
                    'code' => $sales->product_code,
                    'barcode' => $products->product->barcode,
                    'weight' => $sales->weight,
                    'product_store_id' => $sales->product_store_id,
                    'stock' => 1,
                    'type' => $products->product->type,
                    'sale_qty'=>$sales->quantity
                ],
            ]);
        }
    }

    //cancel order
    public function cancel()
    {
        Cart::instance('sales_return')->destroy();
        session()->flash('return_customer');
        return redirect()->route('live.sales.return.create');
    }

    //warehouse search
    public function productSearch($value)
    {
        if ($value == 0) {
        } else {
            $this->product_store_id = $value;
            $this->product_store_name = Store::find($value)->name;
        }
    }

    public function get_previous_balance($customer_id, $date){
        session()->flash('balance');
        $data = CustomerLedger::where('customer_id', $customer_id)->where('date', '<', $date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        return $data->balance ?? 0;
    }

    // store supplier info into session
    public function customerInfo()
    {
        $return_date = $this->full_return_date;
        if( $return_date ) {
            $this->balance = $this->get_previous_balance($this->customer_id, $return_date);
        }


        // dd($this->balance);
        $validateData = $this->validate();
        // dd($validateData);
        $customer = session()->get('return_customer');
        if (!$customer) {
            $customer = [
                'customer_id' => $this->customer_id,
                'customer_name' => $this->customer_name,
                'address' => $this->address,
                'mobile' =>  $this->mobile,
                'balance' =>  $this->balance,
                'sales_date' => date('Y-m-d', strtotime($this->date)),
                'return_date' => $return_date,
                'product_store_id' => $validateData['product_store_id'],
                'product_store_name' => $this->product_store_name,
                'sales_invoice_no' => $this->sales_invoice_no,
                'remarks' => $validateData['remarks'],
            ];
            session()->put('return_customer',  $customer);
        } else {
            if (!$customer) {
                $customer = [
                    'customer_id' => $this->customer_id,
                    'customer_name' => $this->customer_name,
                    'address' => $this->address,
                    'mobile' =>  $this->mobile,
                    'balance' =>  $this->balance,
                    'sales_date' => date('Y-m-d', strtotime($this->date)),
                    'return_date' => $return_date,
                    'product_store_id' => $validateData['product_store_id'],
                    'product_store_name' => $this->product_store_name,
                    'sales_invoice_no' => $this->sales_invoice_no,
                    'remarks' => $validateData['remarks'],
                ];
                session()->put('return_customer',  $customer);
            }
        }
        // dd($customer);
        return redirect()->route('live.sales.return.checkout');
    }

    // brand wise search
    public function brandSearch($value)
    {
        $this->brand_id = $value;
    }


    public function render()
    {
        if ($this->customer_search) {

            $customers = customer::find($this->customer_search);
            $this->customer_name = $customers->name;

            $this->address = $customers->address;
            $this->mobile = $customers->mobile;
            $this->customer_id = $customers->id;

            // search by date
            $target_date = date('Y-m-d', strtotime($this->date));
            if ($target_date) {
                $value = CustomerTransactionDetails::where('customer_id', $this->customer_search)
                    ->whereRaw("DATE(date) = ?", [$target_date])
                    ->where('transaction_type', 'sale')
                    ->first();
                $this->products = CustomerTransactionDetails::where('customer_id', $this->customer_search)
                    ->whereRaw("DATE(date) = ?", [$target_date])
                    ->where('transaction_type', 'sale')
                    ->get();

                if ($value) {
                    $this->sales_invoice_no = $value->transaction_id;
                    $this->product_store_id = $value->product_store_id;
                    $this->product_store_name = Store::where('id', $this->product_store_id)->value('name');
                }
            }

            // search by invoice
            if ($this->invoice) {
                $value = CustomerTransactionDetails::where('customer_id', $this->customer_search)
                    ->where('transaction_id', $this->invoice)
                    ->where('transaction_type', 'sale')
                    ->get();
                $this->products = CustomerTransactionDetails::where('customer_id', $this->customer_search)
                    ->where('transaction_id', $this->invoice)
                    ->where('transaction_type', 'sale')
                    ->get();
            }

            if( $this->date || $this->invoice) {
                $this->dispatch('dataUpdated');
            }
        }


        $customers = customer::get();
        $stores = Store::get();
        $brands = Brand::get();
        return view('livewire.sales-return.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
