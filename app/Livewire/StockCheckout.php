<?php

namespace App\Livewire;


use App\Models\Bank;
use App\Models\Brand;
use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\Warehouse;
use App\Models\ProductStore;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class StockCheckout extends Component
{

    public $customer_id;
    public $price_discount;
    public $vat_discount;
    public $date;
    public $product_store_id;
    public $transport_no;
    public $delivery_man;
    public $remarks;
    public $grand_total = 0;
    public $carring;
    public $other_charge;
    public $payment_by;
    public $payment;
    public $balance;
    public $prev_balance;
    public $product_discount;
    public $total_qty;
    public $bank_list;
    public $discount_status;
    public $vat_status;
    public $bank_title;
    public $total_vat;
    public $total_discount;
    public $received_by;
    public $total_amount;
    public $product_store_data;
    public $products;

    public function mount()
    {
        if (!session()->has('product_store_data')) {
            return redirect()->route('product.stock.manage');
        }
        $this->product_store_data = session()->get('product_store_data');
        if (Cart::instance('manage_stock')->count() == 0) {
            return redirect()->route('product.stock.manage');
        }
    }
    // redirect page
    public function back()
    {
        session()->flash('product_store_data');
        return redirect()->route('product.stock.manage');
    }

    // calceal order
    public function cancel()
    {
        Cart::instance('manage_stock')->destroy();
        session()->flash('product_store_data');
        return redirect()->route('product.stock.manage');
    }

    // payment search
    public function paymentSearch($value)
    {
        //dd($value);

        if ($value == 'Bank') {

            //dd('Bank');
            $this->bank_list = 1;
        } elseif ($value == 'Cheque') {

            $this->bank_list = 2;
        } else {
        }
    }

    //search supplier info from here
    public function customerSearch($value)
    {
        $this->customer_id = $value;
    }

    //Purchase store from here
    public function stockStore()
    {
        $product_store_id = session()->get('product_store_data')['id'];
        if (Cart::instance('manage_stock')->count() > 0) {
            foreach (Cart::instance('manage_stock')->content() as $product) {
                $this->total_qty += $product->qty;
                $this->total_amount += $product->qty * $product->price;
                ProductStore::create([
                    'product_id'        => $product->id,
                    'brand_id'          => $product->options->brand_id,
                    'product_store_id'  => $product_store_id,
                    'product_name'      => $product->name,
                    'product_code'      => $product->options->code,
                    'product_quantity'  => $product->qty,
                    'purchase_price'    => $product->price,
                ]);
            }
        }

        // clear all session in selse cart
        Cart::instance('manage_stock')->destroy();
        session()->flash('product_store_data');

        $notification = array('msg' => 'Stock updated successfully!', 'alert-type' => 'info');
        return redirect()->route('product.index',)->with($notification);    // route for go to

    }

    //get discount status
    public function discountType($val)
    {

        $this->discount_status = $val;
    }

    //get vat status
    public function vatType($val)
    {

        $this->vat_status = $val;
    }

    public function otherCharge($key, $val)
    {
        if ($key && $val > 0) {
            $this->$key = $val;
            if ($key == 'payment') {
                $this->balance = $this->grand_total - $val;
            } else {
                $this->grand_total += $val;
            }
        } else {
            $this->$key = 0;
        }
    }

    public function render()
    {
        $this->product_store_data = Session::get('product_store_data');
        $this->products = json_decode(Cart::instance('manage_stock')->content());

        $total_amount = 0;
        foreach ($this->products as $product) {
            $total_amount += $product->qty * $product->price;
        }
        $this->grand_total = $total_amount;

        return view('livewire.stock-checkout')->extends('layouts.admin')->section('main-content');
    }
}
