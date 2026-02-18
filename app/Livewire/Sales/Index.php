<?php

namespace App\Livewire\Sales;

use App\Models\Brand;
use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\PriceGroupProduct;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search, $brand_id;
    public $new_search;
    public $searches;
    public $customer_id;
    public $balance;
    public $date;
    public $full_date;
    public $product_store_id;
    public $transport_no;
    public $delivery_man;
    public $customer_search;
    public $customer_name;
    public $address;
    public $mobile;
    public $invoice_no;
    public $product_store_name;
    public $last_inv_no;
    public $price_group_id;
    public $stock_out;
    public $products;
    public $remarks;
    public $validator;
    public $error_count = 0;
    public $showSidebar = false;

    public function rules()
    {
        return
            [
                'customer_id' => 'required',
                'date' => ['nullable'],
                'address' => ['nullable'],
                'mobile' => ['nullable'],
                'product_store_id' => 'required',
                'transport_no' => ['nullable'],
                'delivery_man' => ['nullable'],
                'remarks' => ['nullable'],
                'invoice_no' => ['nullable'],
            ];
    }

    public function messages()
    {
        return [
            'customer_id.required'  => 'Please select a customer',
            'product_store_id'      => 'Please select a store',
        ];
    }

    public function mount()
    {

        $this->full_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime(now()))));
        $this->date = date('d-m-Y', strtotime($this->full_date));
        $this->showSidebar = session()->has('showSidebar') ? session()->get('showSidebar') : false;
    }

    public function updatedDate($date){
        $this->full_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($date))));
        $this->date = $date;
    }


    //Increment cart product
    public function updateQuantity($id, $store, $quantities)
    {
        $this->error_count = 0;

        // $result =  $this->products[$id]['qty'] ?? 0;
        foreach (Cart::instance('sales')->content() as $item) {

            if ($item->id == $id) {
                $item->qty = $quantities;
                // $item->options->stock = $result;
                if ($item->options->stock < $quantities) {
                    $this->error_count++;
                }
            }


        }
    }

    //Increment cart product
    public function updateDiscount($id, $discounts)
    {

        foreach (Cart::instance('sales')->content() as $item) {
            if ($item->id == $id) {
                $item->options->discount = $discounts;
            }
        }

    }

    //Increment cart product
    public function updatePrice($id, $update_price)
    {
        foreach (Cart::instance('sales')->content() as $item) {
            if ($item->id == $id) {
                $item->price = $update_price;
            }
        }
    }


    //remove product from cart
    public function itemRemove($rowId)
    {

        $cart = Cart::instance('sales')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            Cart::remove($rowId);
        }
    }

    // add product to sales cart
    public function sessionStore($id)
    {
        if(!$id){
            return;
        }

        $products = ProductStore::where('product_id', $id)
            ->where('product_store_id', $this->product_store_id)
            ->first();

        // dd($products);

        $product_stock = $this->products[$id]['qty'] ?? 0;

        $product = Product::where('id', $id)->first();

        $price_group_rate = PriceGroupProduct::where('price_group_id', $this->price_group_id)->where('product_id', $id)->value('price_group_rate');

        Cart::instance('sales')->add([
            'id' =>  $products->product_id,
            'name' => $products->product_name,
            'qty' => 1,
            'price' => $price_group_rate ?? $products->product->price_rate,
            'options' => [
                'code' => $products->product_code,
                'barcode' => $products->product->barcode,
                'discount' => 0,
                'weight' => $product->size->name,
                'product_store_id' => $products->product_store_id,
                'stock' => $product_stock,
                'type' => $products->product->type]
        ]);
    }

    public function toggleSidebar()
    {
        $this->showSidebar = !$this->showSidebar;
        session()->put('showSidebar', $this->showSidebar);
    }


    //canceal order
    public function canceal()
    {
        Cart::instance('sales')->destroy();
        session()->flash('customer');
        session()->flash('balance');
        return redirect()->route('live.sales.create');
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

    // store customer info into session
    public function customerInfo()
    {
        if ($this->error_count > 0) {
            return;
        }
        $validateData = $this->validate();

        $customer = session()->get('customer');

        if (!$customer) {
            $customer = [
                $this->customer_id => [
                    'customer_id' => $this->customer_id,
                    'customer_name' => $this->customer_name,
                    'address' => $validateData['address'] ?? $this->address,
                    'mobile' =>  $validateData['mobile'] ?? $this->mobile,
                    'balance' =>  $this->balance,
                    'date' => $this->full_date,
                    'product_store_id' => $validateData['product_store_id'],
                    'product_store_name' => $this->product_store_name,
                    'invoice_no' => $this->last_inv_no,
                    'remarks' => $this->remarks,
                    'transport_no' => $validateData['transport_no'],
                    'delivery_man' => $validateData['delivery_man'],
                ]
            ];

            // dd($customer);

            session()->put('customer',  $customer);
        } else {
            if (!$customer) {
                $customer = [
                    $this->customer_id => [
                        'customer_id' => $this->customer_id,
                        'customer_name' => $this->customer_name,
                        'address' => $this->address,
                        'mobile' =>  $this->mobile,
                        'balance' =>  $this->balance,
                        'date' => $this->full_date,
                        'product_store_id' => $validateData['product_store_id'],
                        'product_store_name' => $this->product_store_name,
                        'invoice_no' => $this->last_inv_no,
                        'transport_no' => $validateData['transport_no'],
                        'delivery_man' => $validateData['delivery_man'],
                    ]
                ];
                session()->put('customer',  $customer);

                // dd($customer);
            }
        }
        return redirect()->route('live.sales.checkout');
    }

    // brand wise search
    public function brandSearch($value)
    {
        $this->brand_id = $value;
    }

    public function get_previous_balance($customer_id, $date){
        session()->flash('balance');
        $data = CustomerLedger::where('customer_id', $customer_id)->where('date', '<=', $date)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
        return $data->balance ?? 0;
    }


    public function render()
    {

        if ($this->customer_search) {

            $customers = customer::find($this->customer_search);
            // dd($customers);
            $this->customer_name = $customers->name;

            $this->balance = $this->get_previous_balance($customers->id, $this->full_date);
            $this->address = $customers->address;
            $this->mobile = $customers->mobile;
            $this->customer_id = $customers->id;
            $this->price_group_id = $customers->price_group_id;

            session()->put('balance', $customers->balance);

            $this->dispatch('dataUpdated');
        }


        if ($this->product_store_id) {

            // brand wise product search
            $source_store_products = ProductStore::where('product_store_id', $this->product_store_id)
                // ->where('product_quantity', '!=', 0)
                ->orderBy('product_code', 'asc')
                ->get();
                // dd($source_store_products);


            if ($this->brand_id && $this->product_store_id) {
                $source_store_products = ProductStore::where('product_store_id', $this->product_store_id)
                    // ->where('product_quantity', '!=', 0)
                    ->where('brand_id', $this->brand_id)
                    ->get();
            } elseif ($this->search && $this->product_store_id) {
                $source_store_products = ProductStore::where('product_store_id', $this->product_store_id)
                    // ->where('product_quantity', '!=', 0)
                    ->where('product_name', 'Like', "%{$this->search}%")
                    ->orWhere('product_code', 'Like', "%{$this->search}%")
                    ->get();

            }

            // dd($source_store_products);
            $this->products = $source_store_products->groupBy('product_id')
            ->map(function ($items) {
                $sale_price = $items->first()->product->price_rate;
                return [
                    'name' => $items->first()->product->name,
                    'code' => $items->first()->product->code,
                    'qty' => $items->sum('product_quantity'),
                    'type' => $items->first()->product->type,
                    'price' => $sale_price,
                    'photo' => $items->first()->product->photo
                ];
            })
            ->filter(function ($product) {
                return $product['qty'] > 0; // Keep only products with quantity > 0
            });
            $this->dispatch('dataUpdated');
        } else {

            //$this->products = ProductStore::latest()->get();

        }


        $customers = customer::get();
        $stores = Store::where('status', 1)->get();
        $brands = Brand::get();
        // if($this->products){
        //     dd($source_store_products);
        // }
        return view('livewire.sales.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
