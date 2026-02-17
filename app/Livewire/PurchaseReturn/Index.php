<?php

namespace App\Livewire\PurchaseReturn;

use App\Models\Brand;
use App\Models\ProductStore;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use App\Models\Warehouse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Index extends Component
{
    public $search, $brand_id;
    public $new_search;
    public $searches;
    public $supplier_id;
    public $previous_due;
    public $advance_pay;
    public $date;
    public $return_date;
    public $full_return_date;
    public $product_store_id;
    public $delivery_man;
    public $supplier_search;
    public $supplier_name;
    public $address;
    public $mobile;
    public $invoice_no;
    public $product_store_name;
    public $purchase_invoice_no;
    public $price_group_id;
    public $stock_out;
    public $products;
    public $warehouse_id;
    public $warehouse_name;
    public $remarks;
    public $balance;

    public function rules()
    {
        return
            [
                'remarks' => ['nullable'],
                // 'previous_due' => ['nullable'],
                'date' => ['nullable'],
                'product_store_id' => ['nullable'],
                'delivery_man' => ['nullable'],
                'invoice_no' => ['nullable'],
            ];
    }

    //Increment cart product
    public function updateQuantity($id, $invoice_no, $quantities)
    {
        $purchase_data = SupplierTransactionDetails::where('transaction_id', $invoice_no)->where('product_id', $id)->first();
        // dd($purchase_data->quantity, $quantities);
        foreach (Cart::instance('purchase_return')->content() as $item) {
            if ($quantities <= $purchase_data->quantity ) {
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
        foreach (Cart::instance('purchase_return')->content() as $item) {
            if ($item->id == $id) {
                $item->options->discount = $discounts;
            }
        }
    }

    //Increment cart product
    public function updatePrice($id, $update_price)
    {
        foreach (Cart::instance('purchase_return')->content() as $item) {
            if ($item->id == $id) {
                $item->price = $update_price;
            }
        }
    }


    //remove product from cart
    public function itemRemove($rowId)
    {
        $cart = Cart::instance('purchase_return')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            Cart::remove($rowId);
        }
    }

    // public function dateSearch($date)
    // {
    //     $this->formatted_date = $date;
    //     $this->date = date('Y-m-d', strtotime($date));
    // }

    // public function returnDateSet($date)
    // {
    //     $this->formatted_return_date = $date;
    //     $this->return_date = date('Y-m-d', strtotime($date));
    // }

    // add product to sales cart
    public function sessionStore($id)
    {
        $products = ProductStore::where('product_id', $id)->first();
        $purchase = SupplierTransactionDetails::where('supplier_id', $this->supplier_search)->where('transaction_id', $this->purchase_invoice_no)->where('product_id', $id)->first();
        Cart::instance('purchase_return')->add([
            'id' =>  $products->product_id,
            'name' => $products->product_name,
            'qty' => 1,
            'price' => $purchase->unit_price ?? $products->product->price_rate,
            'options' => [
                'transaction_id' => $purchase->id,
                'code' => $products->product_code,
                'weight' => $purchase->weight,
                'product_store_id' => $products->product_store_id,
                'stock' => $purchase->quantity,
                'type' => $products->product->type,
                'purchased_qty' => $purchase->quantity,
            ]
        ]);
    }


    //cancel order
    public function cancel()
    {
        Cart::instance('purchase_return')->destroy();
        session()->flash('return_supplier');
        session()->flash('supplier_balance');
        session()->flash('purchase_invoice_no');
        return redirect()->route('live.purchase.return.create');
    }

    //warehouse search
    // public function productSearch($value)
    // {
    //     if ($value == 0) {
    //     } else {
    //         $this->product_store_id = $value;
    //         $this->product_store_name = Store::find($value)->name;
    //     }
    // }
    // store supplier info into session
    public function supplierInfo()
    {
        $validateData = $this->validate();
        session()->put('supplier_balance',  $this->balance);
        session()->put('purchase_invoice_no',  $this->purchase_invoice_no);

        $return_supplier = session()->get('return_supplier');
        if (!$return_supplier) {
            $return_supplier = [
                'supplier_id' => $this->supplier_id,
                'supplier_name' => $this->supplier_name,
                'balance' => $this->balance,
                'address' => $this->address,
                'mobile' =>  $this->mobile,
                'purchase_date' => date('Y-m-d', strtotime($this->date)),
                'return_date' => $this->full_return_date,
                'warehouse_id' => $this->warehouse_id,
                'warehouse_name' => $this->warehouse_name,
                'product_store_id' => $validateData['product_store_id'],
                'product_store_name' => $this->product_store_name,
                'purchase_invoice_no' => $this->purchase_invoice_no,
                'delivery_man' => $validateData['delivery_man'],
                'remarks' => $validateData['remarks'],
            ];

        }
        session()->put('return_supplier',  $return_supplier);
        return redirect()->route('live.purchase.return.checkout');
    }

    // brand wise search
    public function brandSearch($value)
    {
        $this->brand_id = $value;
    }

    public function get_previous_balance($supplier_id, $date){
        session()->flash('balance');
        $data = SupplierLedger::where('supplier_id', $supplier_id)->where('date', '<=', $date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        return $data->balance ?? 0;
    }

    public function render()
    {
        if ($this->supplier_search) {
            $suppliers = Supplier::find($this->supplier_search);
            $this->supplier_name = $suppliers->company_name;
            $this->address = $suppliers->address;
            $this->mobile = $suppliers->mobile;
            $this->supplier_id = $suppliers->id;

            if( $this->full_return_date ) {
                $this->balance = $this->get_previous_balance($this->supplier_id, $this->full_return_date);
            }

            $target_date = date('Y-m-d', strtotime($this->date));

            if ($target_date) {
                $value = SupplierTransactionDetails::where('supplier_id', $this->supplier_search)
                    ->whereRaw("DATE(date) = ?", [$target_date])
                    ->where('transaction_type', 'purchase')
                    ->first();
                if ($value) {
                    $this->purchase_invoice_no = $value->transaction_id;
                    $this->warehouse_id = $value->warehouse_id;
                    $this->product_store_id = $value->product_store_id;
                    $this->product_store_name = Store::where('id', $this->product_store_id)->value('name');
                    $this->warehouse_name = Warehouse::where('id', $this->warehouse_id)->value('name');
                }
            }elseif ($this->purchase_invoice_no) {
                $value = SupplierTransactionDetails::where('supplier_id', $this->supplier_search)
                    ->where('transaction_id', $this->purchase_invoice_no)
                    ->first();
                if ($value) {
                    $this->date = $value->date;
                    $this->warehouse_id = $value->warehouse_id;
                    $this->product_store_id = $value->product_store_id;
                    $this->product_store_name = Store::where('id', $this->product_store_id)->value('name');
                    $this->warehouse_name = Warehouse::where('id', $this->warehouse_id)->value('name');
                }
            }
        }


        if ($this->supplier_search && $this->purchase_invoice_no) {

            // brand wise product search
            $this->products = SupplierTransactionDetails::where('supplier_id', $this->supplier_search)->where('transaction_id',  $this->purchase_invoice_no)->get();
            $this->dispatch('dataUpdated');
        }


        $suppliers = Supplier::get();
        $stores = Store::get();
        $brands = Brand::get();
        return view('livewire.purchase-return.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
