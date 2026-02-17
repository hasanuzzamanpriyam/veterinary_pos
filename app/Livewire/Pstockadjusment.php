<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\customer;
use App\Models\PriceGroupProduct;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use App\Models\Warehouse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Pstockadjusment extends Component
{



    public $search, $brand_id;
    public $new_search;
    public $searches;
    public $customer_id;
    public $previous_due;
    public $advance_pay;
    public $date;
    public $source_store_id;
    public $destination_store_id;
    public $transport_no;
    public $delivery_man;
    public $customer_search;
    public $customer_name;
    public $address;
    public $mobile;
    public $invoice_no;
    public $source_store_name;
    public $destination_store_name;
    public $last_inv_no;
    public $price_group_id;
    public $stock_out;
    public $products;
    public $remarks;
    public $validator;

    public function rules()
    {
        return
            [
                'source_store_id' => 'required',
                'destination_store_id' => 'required',
                'remarks' => 'nullable',
            ];
    }

    public function messages()
    {
        return [
            'source_store_id'      => 'Please select a source store',
            'destination_store_id' => 'Please select a destination store',
        ];
    }

    public function source_store_id_update($value)
    {
        if ($value == 0) {
        } else {
            $this->source_store_id = $value;
            $this->source_store_name = Store::find($value)->name;
        }
    }
    public function destination_store_id_update($value)
    {
        if ($value == 0) {
        } else {
            $this->destination_store_id = $value;
            $this->destination_store_name = Store::find($value)->name;
        }
    }

    public function updateQuantity($id, $quantities)
    {
        foreach (Cart::instance('stock_adjust')->content() as $item) {
            if ($item->id == $id) {
                $item->qty = $quantities;
            }
        }
    }

    public function updatePrice($id, $update_price)
    {
        foreach (Cart::instance('stock_adjust')->content() as $item) {
            if ($item->id == $id) {
                $item->price = $update_price;
            }
        }
    }

    public function itemRemove($rowId)
    {

        $cart = Cart::instance('stock_adjust')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            Cart::remove($rowId);
        }
    }

    public function sessionStore($id)
    {
        $product = Product::where('id', $id)->first();
        Cart::instance('stock_adjust')->add([
            'id' =>  $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->purchase_rate,
            'options' => [
                'code' => $product->code,
                'brand_id' => $product->brand_id,
                'discount' => 0,
                'weight' => $product->size->name,
                'product_store_id' => $product->product_store_id,
                'stock' => $this->products[$id]['qty'] ?? 0,
                'type' => $product->type
            ]
        ]);
    }

    public function stockAdjustment()
    {
        $validateData = $this->validate();

        // dd($validateData);

        // we need to find both stores by id
        $source_store = Store::find($validateData['source_store_id']);
        $destination_store = Store::find($validateData['destination_store_id']);
        $adjust_remarks = $validateData['remarks'];

        session()->put('store_stock_adjust', [
            'source_store' => [
                'id' => $validateData['source_store_id'],
                'name' => $source_store->name,
                'address' => $source_store->address,
                'mobile' => $source_store->mobile,
                'email' => $source_store->email,
                'status' => $source_store->status,
                'description' => $source_store->description,
                'remarks' => $source_store->remarks
            ],
            'destination_store' => [
                'id' => $validateData['destination_store_id'],
                'name' => $destination_store->name,
                'address' => $destination_store->address,
                'mobile' => $destination_store->mobile,
                'email' => $destination_store->email,
                'status' => $destination_store->status,
                'description' => $destination_store->description,
                'remarks' => $destination_store->remarks
            ],
            'remarks' => $adjust_remarks
        ]);

        return redirect()->route('live.pstockadjustment.checkout');
    }

    public function render()
    {
        if ($this->source_store_id || $this->destination_store_id || Cart::instance('stock_adjust')->count() > 0) {
            $this->dispatch('dataUpdated');
        }
        $stores = Store::where('status', 1)->get();
        if ($this->source_store_id) {
            $source_store_products = ProductStore::where('product_store_id', $this->source_store_id)->get();
            $this->products = $source_store_products->groupBy('product_id')->map(function ($items) {
                return [
                    'name' => $items->first()->product->name,
                    'code' => $items->first()->product->code,
                    'qty' => $items->sum('product_quantity'),
                    'type' => $items->first()->product->type,
                    'price' => $items->last()->purchase_price
                ];
            });
        }

        return view('livewire.pstockadjusment', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
