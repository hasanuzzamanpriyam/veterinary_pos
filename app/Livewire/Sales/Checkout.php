<?php

namespace App\Livewire\Sales;

use App\Models\Bank;
use App\Models\Brand;
use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\Warehouse;
use App\Models\ProductStore;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Checkout extends Component
{

    public $customer;
    public $customer_id;
    public $price_discount = 0;
    public $vat_discount = 0;
    public $date;
    public $product_store_id;
    public $transport_no;
    public $delivery_man;
    public $remarks;
    public $grand_total = 0;
    public $carring = 0;
    public $other_charge = 0;
    public $payment_by;
    public $payment = 0;
    public $balance = 0;
    public $prev_balance = 0;
    public $product_discount = 0;
    public $total_qty = 0;
    public $bank_list;
    public $discount_status;
    public $vat_status;
    public $bank_title;
    public $total_vat = 0;
    public $total_discount = 0;
    public $received_by;
    public $total_amount_after_discount = 0;


    public function mount()
    {
        if (!session()->has('customer')) {
            return redirect()->to(route('live.sales.create'));
        } else {
            $this->customer = session()->get('customer');
            if (is_array($this->customer)) {
                foreach ($this->customer as $key => $customer) {
                    $this->customer_id = $key;
                    $this->prev_balance = $customer['balance'];
                }
            }
        }
    }

    public function rules()
    {
        return
            [
                'carring' => ['nullable'],
                'other_charge' => ['nullable'],
                'payment_by' => ['nullable'],
                'received_by' => ['nullable'],
                'bank_title' => ['nullable'],
                'payment' => ['nullable'],
                'balance' => ['nullable'],
                'transport_no' => ['nullable'],
                'delivery_man' => ['nullable'],
                'remarks' => ['nullable'],
            ];
    }

    // redirect page
    public function back()
    {
        session()->flash('customer');
        session()->flash('sales_old_due');
        session()->flash('sales_adv_pay');
        return redirect()->route('live.sales.create');
    }

    // calceal order
    public function cancel()
    {
        Cart::instance('sales')->destroy();
        session()->flash('customer');
        session()->flash('sales_old_due');
        session()->flash('sales_adv_pay');
        return redirect()->route('live.sales.create');
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
    public function salesStore()
    {

        // dd(Cart::instance('sales')->content());

        $validateData = $this->validate();
        // dd(Session::get('customer'));
        $date = null;

        if (Cart::instance('sales')->count() > 0) {
            foreach (Cart::instance('sales')->content() as $product) {
                $this->total_qty += $product->qty;
                $this->product_discount += $product->options->discount;
                $this->total_amount_after_discount += ($product->qty - $product->options->discount) * $product->price;
            }
        }

        if (session()->has('customer')) {
            foreach (Session::get('customer') as $value) {
                // chage date format from 21-05-2014 to 2014-05-21
                $date = $value['date'];
                // dd($date);
                $final_balance = $validateData['balance'];
                $inv = DB::transaction(function () use ($value, $date, $final_balance, $validateData) {
                    $balance = $final_balance;

                    // Get all rows > u_id before insert
                    $rowsBeforeInsert = CustomerLedger::where('customer_id', $value['customer_id'])
                        ->where('date', '>', $date)
                        ->orderBy('date', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();

                    $invoice = CustomerLedger::insertGetId([
                        'customer_id' => $value['customer_id'],
                        'product_store_id' => $value['product_store_id'],
                        'type' => 'sale',
                        'payment_by' => $validateData['payment_by'],
                        'received_by' => $validateData['received_by'],
                        'bank_title' => $validateData['bank_title'],
                        'delivery_man' => $value['delivery_man'],
                        'transport_no' => $value['transport_no'],
                        'total_qty' => $this->total_qty,
                        'product_discount' => $this->product_discount,
                        'balance' => $balance,
                        'vat' => $this->total_vat,
                        'carring' => $validateData['carring'],
                        'price_discount' => $this->total_discount,
                        'total_price' => $this->total_amount_after_discount,
                        'other_charge' => $validateData['other_charge'],
                        'payment' => $validateData['payment'],
                        'remarks' => $value['remarks'],
                        'date' => $date,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $toatl_rows_remain = count($rowsBeforeInsert);
                    if ($toatl_rows_remain > 0) {
                        foreach ($rowsBeforeInsert as $row) {
                            $toatl_rows_remain--;
                            // $line_total = $row->total_price - $row->price_discount + $row->vat + $row->carring + $row->other_charge - $row->payment;
                            $total_price = $row->type == 'return' ? -$row->total_price : $row->total_price;
                            $line_total = $total_price - $row->price_discount + $row->vat + $row->other_charge + $row->carring - $row->payment;
                            $balance += $line_total;
                            $row->balance = $balance;
                            $row->save();
                        }
                    }
                    customer::where('id', $value['customer_id'])->update([
                        'balance' => $balance
                    ]);

                    customer::where('id', $value['customer_id'])->update([
                        'name' => $value['customer_name'],
                        'address' => $value['address'],
                        'mobile' => $value['mobile'],
                    ]);

                    if (Cart::instance('sales')->count() > 0) {
                        foreach (Cart::instance('sales')->content() as $product) {
                            foreach (Session::get('customer') as $value) {

                                CustomerTransactionDetails::insert([
                                    'customer_id' => $value['customer_id'],
                                    'transaction_id' => $invoice,
                                    'product_store_id' => $value['product_store_id'],
                                    'product_id' => $product->id,
                                    'product_code' => $product->options->code,
                                    'product_name' => $product->name,
                                    'unit_price' => $product->price,
                                    'quantity' => $product->qty,
                                    'weight' => $product->options->weight,
                                    'discount_qty' => $product->options->discount,
                                    'return_qty' => 0,
                                    'total_price' => ($product->qty - $product->options->discount) * $product->price,
                                    'date' => $date,
                                    'transaction_type' => 'sale',
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);

                                // check if the product is exists in this table
                                $product_store = ProductStore::where(
                                    [
                                        'product_id' => $product->id,
                                        'product_store_id' => $value['product_store_id']
                                    ]
                                )->first();

                                //if exists then decrement the product quantity
                                if ($product_store) {
                                    $product_store->decrement('product_quantity', $product->qty);
                                }
                            }
                        }
                    }
                    return $invoice;
                });
            }
        }

        // clear all session in selse cart
        Cart::instance('sales')->destroy();
        session()->flash('customer');
        session()->flash('balance');

        $notification = array('msg' => 'Order Successfully Submited!', 'alert-type' => 'info');

        // return redirect()->route('sales.invoice',$invoice)->with($notification); // route for go to pdf invoice
        return redirect()->route('sales.view', $inv)->with($notification);    // route for go to

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



        $total_amount = 0;
        foreach (Cart::instance('sales')->content() as $product) {
            $total_amount += ($product->qty - $product->options->discount) * $product->price;

            //   dd($total_amount);
        }
        $this->grand_total = $total_amount;



        //discount calculation
        if ($this->discount_status) {

            if ($this->discount_status == 1) {
                // dd($this->discount."tk");
                $this->total_discount = floatval($this->price_discount);
                $this->grand_total -= floatval($this->price_discount);
            } else {
                //dd($this->discount."%");
                $total = $this->grand_total;

                $this->total_discount = $total * floatval($this->price_discount) / 100;

                $this->grand_total -= $this->total_discount;
            }
        }

        //vat discount calculation
        if ($this->vat_status) {

            if ($this->vat_status == 1) {
                //dd($this->discount."tk");
                $this->total_vat = floatval($this->vat_discount);
                $this->grand_total += floatval($this->vat_discount);
            } else {
                //dd($this->discount."%");
                $total = $this->grand_total;
                $this->total_vat = $total * floatval($this->vat_discount) / 100;
                $this->grand_total += $this->total_vat;
                //$this->vat_discount=$dis;

                //dd($this->total_vat);

            }
        }
        //ocarring calculation
        if ($this->carring) {
            $this->grand_total += $this->carring;
        }
        //other charge calculation
        if ($this->other_charge) {
            $this->grand_total += $this->other_charge;
        }
        //payment calculation
        $this->balance = $this->grand_total  + $this->prev_balance - $this->payment;



        $banks = Bank::get();
        $customers_info = Session::get('customer');
        //$this->previous_due =
        $customers = customer::get();
        $warehouses = Warehouse::get();
        $brands = Brand::get();
        return view('livewire.sales.checkout', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
