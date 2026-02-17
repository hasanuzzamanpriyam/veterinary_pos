<?php

namespace App\Livewire\Purchase;

use App\Models\Bank;
use App\Models\Brand;

use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\ProductStore;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Checkout extends Component
{
    public $supplier;
    public $price_discount;
    public $vat_discount = 0;
    public $transport_no;
    public $delivery_man;
    public $payment_remarks;
    public $total_tk = 0;
    public $grand_total = 0;
    public $carring = 0;
    public $other_charge = 0;
    public $payment_by;
    public $payment = 0;
    public $balance = 0;
    public $prev_balance = 0;
    // public $current_due;
    public $product_discount = 0;
    // public $old_due;
    public $total_qty = 0;
    public $bank_list;
    public $discount_status;
    public $vat_status;
    public $bank_title;
    public $total_vat = 0;
    public $total_discount = 0;
    public $total_amount_after_discount = 0;


    public function mount()
    {
        $this->supplier = session()->has('supplier') ? session()->get('supplier') : null;
    }


    public function rules()
    {
        return [
            'carring'           => ['nullable', 'numeric'],
            'other_charge'      => ['nullable', 'numeric'],
            'payment_by'        => ['nullable'],
            'bank_title'        => ['nullable'],
            'payment'           => ['nullable', 'numeric'],
            'balance'           => ['nullable', 'numeric'],
            'transport_no'      => ['nullable'],
            'delivery_man'      => ['nullable'],
            'payment_remarks'   => ['nullable'],
            'total_vat'         => ['nullable', 'numeric'],
        ];
    }

    // redirect page
    public function back()
    {
        session()->flash('supplier');
        session()->flash('pre_due');
        session()->flash('adv_pay');
        return redirect()->route('live.purchase.create');
    }

    // calceal order
    public function cancel()
    {
        Cart::instance('purchase')->destroy();
        session()->flash('supplier');
        session()->flash('pre_due');
        session()->flash('adv_pay');
        return redirect()->route('live.purchase.create');
    }

    // payment search
    public function paymentSearch($value)
    {
        //dd($value);

        if ($value == 'Bank') {

            $this->bank_list = 1;
        } elseif ($value == 'Cheque') {

            $this->bank_list = 2;
        } else {
        }
    }

    //Purchase store from here
    public function purchaseStore()
    {

        $inv = null;
        $validateData = $this->validate();
        // dd($validateData);


        if (Cart::instance('purchase')->count() > 0) {
            foreach (Cart::instance('purchase')->content() as $product) {
                $this->total_qty += $product->qty;
                $this->product_discount += $product->options->discount;
                $this->total_amount_after_discount += ($product->qty - $product->options->discount) * $product->price;
            }
        }

        if ($this->supplier) {
            $supplier = $this->supplier;
            $final_balance = $supplier['balance'] + $this->total_tk - $this->grand_total;
            $date = $supplier['date'];
            $inv = DB::transaction(function () use ($supplier, $date, $final_balance, $validateData) {

                $rowsBeforeInsert = SupplierLedger::where('supplier_id', $supplier['supplier_id'])
                    ->where('date', '>', $date)
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                $invoice = SupplierLedger::insertGetId([
                    'supplier_id'       => $supplier['supplier_id'],
                    'warehouse_id'      => $supplier['warehouse_id'],
                    'product_store_id'  => $supplier['product_store_id'],
                    'type'              => 'purchase',
                    'payment_by'        => $validateData['payment_by'],
                    'bank_title'        => $validateData['bank_title'],
                    'delivery_man'      => $supplier['delivery_man'],
                    'transport_no'      => $supplier['transport_no'],

                    'total_qty'         => $this->total_qty,
                    'product_discount'  => $this->product_discount,
                    'balance'           => $this->balance,
                    'vat'               => $this->total_vat,
                    'carring'           => $validateData['carring'] ?? 0,
                    'price_discount'    => $this->total_discount,
                    'total_price'       => $this->total_amount_after_discount,
                    'other_charge'      => $validateData['other_charge'] ?? 0,
                    'payment'           => $validateData['payment'] ?? 0,
                    'payment_remarks'   => $validateData['payment_remarks'],
                    'supplier_remarks'  => $supplier['supplier_remarks'],
                    'date'              => $date,
                    'created_at'        => now(),
                    'updated_at'        => now()
                ]);

                $toatl_rows_remain = count($rowsBeforeInsert);
                if ($toatl_rows_remain > 0) {
                    foreach ($rowsBeforeInsert as $row) {
                        $total_price = 0;
                        $toatl_rows_remain--;
                        $total_price = $row->type == 'return' ? -$row->total_price : $row->total_price;
                        $line_total = $total_price - $row->price_discount - $row->vat - $row->other_charge - $row->carring - $row->payment;
                        $final_balance += $line_total;
                        $row->balance = $final_balance;
                        $row->save();
                    }
                }

                //update supplier info
                Supplier::where('id', $supplier['supplier_id'])->update([
                    'company_name'  => $supplier['supplier_name'],
                    'address'       => $supplier['address'],
                    'mobile'        => $supplier['mobile'],
                ]);

                // update supplier balance
                Supplier::where('id', $supplier['supplier_id'])->update([
                    'balance'      => $final_balance
                ]);

                if (Cart::instance('purchase')->count() > 0) {
                    foreach (Cart::instance('purchase')->content() as $product) {
                        SupplierTransactionDetails::insert(
                            [
                                'supplier_id'       => $supplier['supplier_id'],
                                'transaction_id'    => $invoice,
                                'warehouse_id'      => $supplier['warehouse_id'],
                                'product_store_id'  => $supplier['product_store_id'],
                                'product_id'        => $product->id,
                                'product_code'      => $product->options->code,
                                'product_name'      => $product->name,
                                'quantity'          => $product->qty,
                                'discount_qty'      => $product->options->discount,
                                'weight'            => $product->options->weight,
                                'unit_price'        => $product->price,
                                'total_price'       => ($product->qty - $product->options->discount) * $product->price,
                                'transaction_type'  => 'purchase',
                                'date'              => $date,
                                'created_at'        => now(),
                                'updated_at'        => now()
                            ]
                        );

                        $product_store = ProductStore::where([
                            'product_id'        => $product->id,
                            'product_store_id'  => $supplier['product_store_id']
                        ])->first();

                        if ($product_store) {
                            $product_store->increment('product_quantity', $product->qty);
                        } else {
                            ProductStore::create([
                                'product_id'        => $product->id,
                                'brand_id'          => $product->options->brand_id,
                                'product_store_id'  => $supplier['product_store_id'],
                                'product_name'      => $product->name,
                                'product_code'      => $product->options->code,
                                'product_quantity'  => $product->qty,
                                'purchase_price'    => $product->price,
                            ]);
                        }
                    }
                }
                return $invoice;
            });
        }



        // clear all session
        Cart::instance('purchase')->destroy();
        session()->flash('supplier');

        $notification = array(
            'msg'           => 'Order Successfully Submited!',
            'alert-type'    => 'info'
        );

        return redirect()->route('purchase.view', $inv)->with($notification);
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

    public function render()
    {

        $payment = floatval($this->payment);
        if ($this->payment == '') {
            $payment = 0;
        }

        // $adv_pay = Session::get('adv_pay');
        // $pre_due = Session::get('pre_due');
        // dd($this->supplier['balance']);
        $this->prev_balance = $this->supplier['balance'] ?? 0;

        $total_amount = 0;
        foreach (Cart::instance('purchase')->content() as $product) {
            $total_amount += ($product->qty - $product->options->discount) * $product->price;
        }


        //discount calculation
        if ($this->discount_status) {

            if ($this->discount_status == 1) {
                $this->total_discount = floatval($this->price_discount);
            } else {
                $total = $this->total_tk;
                $this->total_discount = $total * floatval($this->price_discount) / 100;
            }
        }

        $this->total_tk = $total_amount - $this->total_discount;

        //vat discount calculation
        if ($this->vat_status) {

            if ($this->vat_status == 1) {
                $this->total_vat = floatval($this->vat_discount);
            } else {
                $total = $this->grand_total;
                $this->total_vat = $total * floatval($this->vat_discount) / 100;
            }
        }
        $this->grand_total = $this->total_vat + floatval($this->carring) + floatval($this->other_charge) + $payment;

        $this->balance = $this->prev_balance + $this->total_tk - $this->grand_total;

        $banks = Bank::get();
        $suppliers = Supplier::get();
        $warehouses = Warehouse::get();
        $brands = Brand::get();
        return view('livewire.purchase.checkout', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
