<?php

namespace App\Livewire\SalesReturn;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\ProductStore;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Checkout extends Component
{
    public $customer_id;
    public $price_discount;
    public $vat_discount;
    public $date;
    public $product_store_id;
    public $transport_no;
    public $delivery_man;
    public $balance;
    public $remarks;
    public $grand_total = 0;
    public $carring;
    public $other_charge;
    public $payment_by;
    public $payment;
    public $current_due;
    public $product_discount;
    public $old_due;
    public $total_qty;
    public $bank_list;
    public $discount_status;
    public $vat_status;

    public function rules()
    {
        return
            [
                'carring' => ['nullable'],
                'other_charge' => ['nullable'],
                'current_due' => ['nullable'],
                'remarks' => ['nullable'],
            ];
    }

    // redirect page
    public function back()
    {
        session()->flash('return_customer');
        return redirect()->route('live.sales.return.create');
    }

    // calceal order
    public function cancel()
    {
        Cart::instance('sales_return')->destroy();
        session()->flash('return_customer');

        return redirect()->route('live.sales.return.create');
    }

    //search supplier info from here
    public function customerSearch($value)
    {
        $this->customer_id = $value;
    }

    //Purchase store from here
    public function salesStore()
    {
        // dd($this->current_due);
        $validateData = $this->validate();
        $error_count = 0;
        $inv = 0;

        if (session()->has('return_customer')) {

            // check if return qty is less than or equal sales qty
            if (Cart::instance('sales_return')->count() > 0) {
                foreach (Cart::instance('sales_return')->content() as $product) {
                    $productQty = $product->qty;
                    $transactionId = $product->options->transaction_id;

                    // Find the current record
                    $transaction = CustomerTransactionDetails::where('id', $transactionId)->first();

                    if ($transaction) {
                        $previousReturnQty = $transaction->return_qty ?? 0;
                        $saleQuantity = $transaction->quantity;
                        if (($previousReturnQty + $productQty) > $saleQuantity) {
                            $error_count++;
                        }
                    }
                }
            }

            // dd($error_count);
            if ($error_count > 0) {
                Cart::instance('sales_return')->destroy();
                $notification = array('msg' => 'Return quantity must be less than or equal to sales quantity', 'alert-type' => 'error');
                return redirect()->route('live.sales.return.create')->with($notification);
            }



            if (Cart::instance('sales_return')->count() > 0) {
                foreach (Cart::instance('sales_return')->content() as $product) {
                    $this->total_qty += $product->qty;
                }
            }

            if( session()->has('return_customer') ){
                $value = Session::get('return_customer');

                $cart_total = Cart::instance('sales_return')->total() - Cart::instance('sales_return')->tax();
                $inv = DB::transaction(function() use ($value, $cart_total, $validateData) {
                    $final_balance = $this->current_due;

                    $rowsBeforeInsert = CustomerLedger::where('customer_id', $value['customer_id'])
                        ->where('date', '>', $value['return_date'])
                        ->orderBy('date', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();

                    $invoice = CustomerLedger::insertGetId([
                        'customer_id' => $value['customer_id'],
                        'product_store_id' => $value['product_store_id'],
                        'sales_invoices' => json_encode($value['sales_invoice_no']),
                        'type' => 'return',
                        'total_qty' => $this->total_qty,
                        'balance' => $this->current_due ?? 0,
                        'carring' => $validateData['carring'] ?? 0,
                        'other_charge' => $validateData['other_charge'] ?? 0,
                        'total_price' => $cart_total ?? 0,
                        'remarks' => $validateData['remarks'],
                        'sale_date' => $value['sales_date'],
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
                            $other_cost = $row->type == 'return' ? -($row->carring + $row->other_charge) : ($row->carring + $row->other_charge);
                            $line_total = $total_price - $row->price_discount + $row->vat + $other_cost - $row->payment;
                            $final_balance += $line_total;
                            $row->balance = $final_balance;
                            $row->save();
                        }
                    }
                    customer::where('id', $value['customer_id'])->update([
                        'balance' => $final_balance
                    ]);

                    // Update customer name address and mobile
                    customer::where('id', $value['customer_id'])->update([
                        'name' => $value['customer_name'],
                        'address' => $value['address'],
                        'mobile' => $value['mobile'],
                    ]);


                    //check sales
                    if (Cart::instance('sales_return')->count() > 0 && $invoice) {

                        foreach (Cart::instance('sales_return')->content() as $product) {

                            CustomerTransactionDetails::where('id', $product->options->transaction_id)->increment('return_qty', $product->qty);

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
                                $product_store->increment('product_quantity', $product->qty);
                            }
                        }
                    }

                    return $invoice;
                });
            }

        }

        // dd("sss");

        // clear all session in selse cart
        Cart::instance('sales_return')->destroy();
        session()->flash('return_customer');

        $notification = array('msg' => 'Sales Return Successfully Submited!', 'alert-type' => 'info');
        return redirect()->route('sales.return.view',['invoice'=> $inv])->with($notification);
    }

    public function render()
    {
        $customer = Session::get('return_customer');
        // dd($customer);
        $sales_return = Cart::instance('sales_return')->content();
        $totalPrice = 0;

        // Iterate over the cart content and calculate the total price
        foreach ($sales_return as $item) {
            $totalPrice += $item->price * $item->qty;
        }

        $this->balance = $customer['balance'];
        $this->grand_total = $totalPrice;
        $this->current_due = $this->balance - $this->grand_total;
        // dd($this->current_due);
        if ($this->carring) {
            $this->grand_total += $this->carring;
            $this->current_due -= $this->carring;
        }

        //other charge calculation
        if ($this->other_charge) {
            $this->grand_total += $this->other_charge;
            $this->current_due -= $this->other_charge;
        }




        $customers_info = Session::get('customer');
        $customers = customer::get();
        return view('livewire.sales-return.checkout', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
