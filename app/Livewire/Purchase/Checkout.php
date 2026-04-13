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
            'carring' => ['nullable', 'numeric'],
            'other_charge' => ['nullable', 'numeric'],
            'payment_by' => ['nullable'],
            'bank_title' => ['nullable'],
            'payment' => ['nullable', 'numeric'],
            'balance' => ['nullable', 'numeric'],
            'transport_no' => ['nullable'],
            'delivery_man' => ['nullable'],
            'payment_remarks' => ['nullable'],
            'total_vat' => ['nullable', 'numeric'],
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
        app('cart')->instance('purchase')->destroy();
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


        // Default to 0; will be recalculated after the cart totals are summed
        $single_discount = 0;
        $total_item_vat = 0;
        $total_item_discount = 0;

        if (app('cart')->instance('purchase')->count() > 0) {
            foreach (app('cart')->instance('purchase')->content() as $product) {
                $this->total_qty += (float) $product->qty;
                $this->product_discount += (float) $product->options->discount;
                $line_value = ((float) $product->qty - (float) $product->options->discount) * (float) $product->price;
                $line_discount = (float) ($product->options->item_discount ?? 0);
                $line_vat = (float) ($product->options->item_vat ?? 0);
                $total_item_vat += $line_vat;
                $total_item_discount += $line_discount;
                $this->total_amount_after_discount += $line_value - $line_discount + $line_vat;
            }
        }

        // Single discount = total price discount divided by total quantity
        $single_discount = $this->total_qty > 0 ? ($this->total_discount / $this->total_qty) : 0;

        if ($this->supplier) {
            $supplier = $this->supplier;
            $final_balance = $supplier['balance'] + $this->total_tk - $this->grand_total;
            $date = $supplier['date'];
            $inv = DB::transaction(function () use ($supplier, $date, $final_balance, $validateData, $single_discount, $total_item_vat, $total_item_discount) {

                $rowsBeforeInsert = SupplierLedger::where('supplier_id', $supplier['supplier_id'])
                    ->where('date', '>', $date)
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                $invoice = SupplierLedger::insertGetId([
                    'supplier_id' => $supplier['supplier_id'],
                    'warehouse_id' => $supplier['warehouse_id'],
                    'product_store_id' => $supplier['product_store_id'],
                    'type' => 'purchase',
                    'payment_by' => $validateData['payment_by'],
                    'bank_title' => $validateData['bank_title'],
                    'delivery_man' => $supplier['delivery_man'],
                    'transport_no' => $supplier['transport_no'],

                    'total_qty' => $this->total_qty,
                    'product_discount' => $this->product_discount,
                    'balance' => $this->balance,
                    'vat' => $total_item_vat,
                    'carring' => $validateData['carring'] ?? 0,
                    'price_discount' => $this->total_discount,
                    'total_price' => $this->total_amount_after_discount,
                    'other_charge' => $validateData['other_charge'] ?? 0,
                    'payment' => $validateData['payment'] ?? 0,
                    'payment_remarks' => $validateData['payment_remarks'],
                    'supplier_remarks' => $supplier['supplier_remarks'],
                    'date' => $date,
                    'purchase_date' => (!empty($supplier['purchase_date'])) ? date('Y-m-d', strtotime($supplier['purchase_date'])) : null,
                    'created_at' => now(),
                    'updated_at' => now()
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
                    'company_name' => $supplier['supplier_name'],
                    'address' => $supplier['address'],
                    'mobile' => $supplier['mobile'],
                ]);

                // update supplier balance
                Supplier::where('id', $supplier['supplier_id'])->update([
                    'balance' => $final_balance
                ]);

                if (app('cart')->instance('purchase')->count() > 0) {
                    foreach (app('cart')->instance('purchase')->content() as $product) {
                        $raw_production_date = $product->options->production_date ?? $supplier['production_date'] ?? null;
                        $raw_expire_date = $product->options->expire_date ?? $supplier['expire_date'] ?? null;
                        $production_date = (!empty($raw_production_date)) ? date('Y-m-d', strtotime($raw_production_date)) : null;
                        $expire_date = (!empty($raw_expire_date)) ? date('Y-m-d', strtotime($raw_expire_date)) : null;

                        $row_total_discount = $single_discount * (float) $product->qty;
                        $row_subtotal = ((float) $product->qty - (float) $product->options->discount) * (float) $product->price;
                        $row_net_amount = $row_subtotal - $row_total_discount - (float)($product->options->item_discount ?? 0) + (float)($product->options->item_vat ?? 0);

                        SupplierTransactionDetails::insert(
                            [
                                'supplier_id' => $supplier['supplier_id'],
                                'transaction_id' => $invoice,
                                'warehouse_id' => $supplier['warehouse_id'],
                                'product_store_id' => $supplier['product_store_id'],
                                'product_id' => $product->id,
                                'product_name' => $product->name,
                                'quantity' => (float) $product->qty,
                                'discount_qty' => (float) $product->options->discount,
                                'weight' => $product->options->weight,
                                'unit_price' => (float) $product->price,
                                'total_price' => $row_subtotal,
                                'value' => $row_subtotal,
                                'discount' => $product->options->item_discount ?? 0,
                                'vat' => $product->options->item_vat ?? 0,
                                'single_discount' => $single_discount,
                                'total_discount' => $row_total_discount,
                                'net_amount' => $row_net_amount,
                                'transaction_type' => 'purchase',
                                'date' => $date,
                                'production_date' => $production_date,
                                'expire_date' => $expire_date,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );

                        $product_store = ProductStore::where([
                            'product_id' => $product->id,
                            'product_store_id' => $supplier['product_store_id'],
                            'production_date' => $production_date,
                            'expire_date' => $expire_date,
                        ])->first();

                        if ($product_store) {
                            $qty_discount_to_add = (float) $product->options->discount;
                            
                            $product_store->increment('product_quantity', $product->qty);
                            
                            if ($qty_discount_to_add > 0) {
                                $product_store->increment('discount_quantity', $qty_discount_to_add);
                            }
                        } else {
                            $qty_discount_to_add = (float) $product->options->discount;
                        
                            ProductStore::create([
                                'product_id' => $product->id,
                                'brand_id' => $product->options->brand_id,
                                'product_store_id' => $supplier['product_store_id'],
                                'product_name' => $product->name,
                                'product_quantity' => $product->qty,
                                'discount_quantity' => $qty_discount_to_add,
                                'purchase_price' => $product->price,
                                'production_date' => $production_date,
                                'expire_date' => $expire_date,
                            ]);
                        }
                    }
                }
                return $invoice;
            });
        }



        // clear all session
        app('cart')->instance('purchase')->destroy();
        session()->flash('supplier');

        $notification = array(
            'msg' => 'Order Successfully Submited!',
            'alert-type' => 'info'
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
        foreach (app('cart')->instance('purchase')->content() as $product) {
            $line_val = ((float) $product->qty - (float) $product->options->discount) * (float) $product->price;
            $line_dis = (float) ($product->options->item_discount ?? 0);
            $line_vat = (float) ($product->options->item_vat ?? 0);
            $total_amount += $line_val - $line_dis + $line_vat;
        }


        //discount calculation
        if ($this->discount_status) {

            if ($this->discount_status == 1) {
                $this->total_discount = floatval($this->price_discount);
            } else {
                // percentage discount applied on total purchase amount
                $this->total_discount = $total_amount * floatval($this->price_discount) / 100;
            }
        }

        $this->total_tk = $total_amount - $this->total_discount;

        //vat calculation
        if ($this->vat_status) {

            if ($this->vat_status == 1) {
                $this->total_vat = floatval($this->vat_discount);
            } else {
                // percentage VAT applied on total_tk (subtotal after discount)
                $this->total_vat = $this->total_tk * floatval($this->vat_discount) / 100;
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

