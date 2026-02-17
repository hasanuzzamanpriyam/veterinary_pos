<?php

namespace App\Livewire\PurchaseReturn;

use App\Models\Bank;
use App\Models\Brand;
use App\Models\ProductStore;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use App\Models\Warehouse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkout extends Component
{
    public $return_amount = 0;
    public $carring = 0;
    public $other_charge = 0;
    public $current_due = 0;
    public $total_qty = 0;
    public $supplier_info;
    public $balance = 0;
    public $total_price = 0;
    public $purchase_invoice_no;
    public $expense = 0;


    public function rules()
    {
        return
            [
                'carring' => ['integer', 'nullable'],
                'other_charge' => ['integer', 'nullable'],
                'current_due' => ['integer', 'nullable'],
            ];
    }

    public function mount(){
        $this->supplier_info = session()->has('return_supplier') ? session()->get('return_supplier') : null;
        $this->balance = session()->has('supplier_balance') ? session()->get('supplier_balance') : 0;
        $this->purchase_invoice_no = session()->has('purchase_invoice_no') ? session()->get('purchase_invoice_no') : null;
    }

    // redirect page
    public function back()
    {
        session()->flash('return_supplier');
        session()->flash('supplier_balance');
        session()->flash('purchase_invoice_no');
        return redirect()->route('live.purchase.return.create');
    }

    // calceal order
    public function cancel()
    {
        Cart::instance('purchase_return')->destroy();
        session()->flash('return_supplier');
        session()->flash('supplier_balance');
        session()->flash('purchase_invoice_no');
        return redirect()->route('live.purchase.return.create');
    }

    //Purchase store from here
    public function purchaseStore()
    {
        $validateData = $this->validate();
        // dd($validateData, $this->supplier_info);
        $error_count = 0;
        $inv = 0;

        if (Cart::instance('purchase_return')->count() > 0) {
            foreach (Cart::instance('purchase_return')->content() as $product) {
                // $this->total_qty += $product->qty;
                $productQty = $product->qty;
                $transactionId = $product->options->transaction_id;

                // Find the current record
                $transaction = SupplierTransactionDetails::where('id', $transactionId)->first();

                if ($transaction) {
                    $previousReturnQty = $transaction->return_qty ?? 0;
                    $purchaseQuantity = $transaction->quantity;
                    if (($previousReturnQty + $productQty) > $purchaseQuantity) {
                        $error_count++;
                    }
                }

            }
        }

        if ($error_count > 0) {
            Cart::instance('purchase_return')->destroy();
            $notification = array('msg' => 'Return quantity must be less than or equal to purchase quantity', 'alert-type' => 'error');
            return redirect()->route('live.purchase.return.create')->with($notification);
        }

        if (Cart::instance('purchase_return')->count() > 0) {
            foreach (Cart::instance('purchase_return')->content() as $product) {
                $this->total_qty += $product->qty;
            }
        }


        if ($this->supplier_info) {
            $value = $this->supplier_info;
            $cart_total = Cart::instance('purchase_return')->total() - Cart::instance('purchase_return')->tax();
            $inv = DB::transaction(function() use ($value, $cart_total, $validateData) {
                $final_balance = $value['balance'] - $cart_total - $this->carring - $this->other_charge;

                $rowsBeforeInsert = SupplierLedger::where('supplier_id', $value['supplier_id'])
                ->where('date', '>', $value['return_date'])
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

                $invoice = SupplierLedger::insertGetId([
                    'supplier_id' => $value['supplier_id'],
                    'warehouse_id' => $value['warehouse_id'],
                    'product_store_id' => $value['product_store_id'],
                    'purchase_invoices' => json_encode($value['purchase_invoice_no']),
                    'type' => 'return',
                    'total_qty' => $this->total_qty,
                    'balance' => $final_balance ?? 0,
                    'vat' => 0,
                    'price_discount' => 0,
                    'payment' => 0,
                    'carring' => $validateData['carring'] ?? 0,
                    'other_charge' => $validateData['other_charge'] ?? 0,
                    'total_price' => $cart_total ?? 0,
                    'supplier_remarks' => $value['remarks'],
                    'purchase_date' => $value['purchase_date'],
                    'date' => $value['return_date'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $toatl_rows_remain = count($rowsBeforeInsert);
                if($toatl_rows_remain > 0){
                    foreach ($rowsBeforeInsert as $row) {
                        $total_price = 0;
                        $toatl_rows_remain--;
                        $total_price = $row->type == 'return' ? -$row->total_price : $row->total_price;
                        $line_total = $total_price - $row->price_discount - $row->vat - $row->carring - $row->other_charge - $row->payment;
                        $final_balance += $line_total;
                        $row->balance = $final_balance;
                        $row->save();
                    }
                }

                Supplier::where('id', $value['supplier_id'])->update([
                    'balance'      => $final_balance
                ]);

                if (Cart::instance('purchase_return')->count() > 0 && $invoice) {

                    foreach (Cart::instance('purchase_return')->content() as $product) {

                        SupplierTransactionDetails::where('id', $product->options->transaction_id)->decrement('return_qty', $product->qty);

                        SupplierTransactionDetails::insert([
                            'supplier_id' => $value['supplier_id'],
                            'transaction_id' => $invoice,
                            'warehouse_id' => $value['warehouse_id'],
                            'product_store_id' => $value['product_store_id'],
                            'product_id' => $product->id,
                            'product_code' => $product->options->code,
                            'product_name' => $product->name,
                            'unit_price' => $product->price,
                            'quantity' => $product->qty,
                            'weight' => $product->options->weight,
                            'total_price' => $product->qty * $product->price,
                            'date' => $value['return_date'],
                            'transaction_type' => 'return',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        // check if the product is exists in this table
                        $product_store = ProductStore::where([
                            'product_id' => $product->id,
                            'product_store_id' => $value['product_store_id']
                        ])->first();

                        //if exists then decrement the product quantity
                        if ($product_store) {
                            $product_store->decrement('product_quantity', $product->qty);
                        }
                    }
                }

                return $invoice;

            });
        }

        // clear all session
        Cart::instance('purchase_return')->destroy();
        session()->flash('return_supplier');
        session()->flash('supplier_balance');
        session()->flash('purchase_invoice_no');

        $notification = array('msg' => 'Purchase return successfully submited!', 'alert-type' => 'info');
        return redirect()->route('purchase.view', ['invoice'=> $inv, 'view' => 'return'])->with($notification);
    }

    public function render()
    {
        $this->total_price = Cart::instance('purchase_return')->total();
        $this->return_amount = Cart::instance('purchase_return')->total();

        if ($this->carring) {
            $this->return_amount += $this->carring;
        }
        //other charge calculation
        if ($this->other_charge) {
            $this->return_amount += $this->other_charge;
        }
        $this->current_due = $this->balance - $this->return_amount;

        $banks = Bank::get();
        $suppliers_info = session()->get('supplier');
        $suppliers = Supplier::get();
        $warehouses = Warehouse::get();
        $brands = Brand::get();
        return view('livewire.purchase-return.checkout', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
