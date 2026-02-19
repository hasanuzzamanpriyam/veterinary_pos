<?php

namespace App\Livewire\Purchase;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\Warehouse;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $supplier_search;
    public $supplier_name;
    public $supplier_id;
    public $search, $brand_id;
    public $new_search;
    public $searches;
    public $customer_id;
    public $balance;
    public $date;
    public $full_date;
    public $warehouse_id;
    public $product_store_id;
    public $transport_no;
    public $delivery_man;
    public $address;
    public $mobile;
    public $warehouse_name;
    public $supplier_remarks;
    public $showSidebar = false;



    public function rules()
    {
        return
            [
                'date' => ['nullable'],
                'warehouse_id' => ['required'],
                'product_store_id' => ['required'],
                'transport_no' => ['nullable'],
                'delivery_man' => ['nullable']
            ];
    }

    public function mount()
    {
        $this->full_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime(now()))));
        $this->date = date('d-m-Y', strtotime($this->full_date));
        $this->showSidebar = session()->has('showSidebar') ? session()->get('showSidebar') : false;
    }

    public function toggleSidebar()
    {
        $this->showSidebar = !$this->showSidebar;
        session()->put('showSidebar', $this->showSidebar);
    }

    public function updatedDate($date){
        $this->full_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($date))));
        $this->date = $date;
    }

    //Increment cart product
    public function updateQuantity($id, $quantities)
    {

        foreach (Cart::instance('purchase')->content() as $item) {
            if ($item->id == $id) {
                $item->qty = $quantities;
            }
        }

        // $this->dispatch('refresh');
    }

    //Increment cart product
    public function updateDiscount($id, $discounts)
    {

        foreach (Cart::instance('purchase')->content() as $item) {
            if ($item->id == $id) {
                $item->options->discount = $discounts;
            }
        }

        //$this->dispatch('refresh');
    }

    //Increment cart product
    public function updatePrice($id, $update_price)
    {
        foreach (Cart::instance('purchase')->content() as $item) {
            if ($item->id == $id) {
                $item->price = $update_price;
            }
        }

        //$this->dispatch('refresh');
    }


    //remove product from cart
    public function itemRemove($rowId)
    {

        $cart = Cart::instance('purchase')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            Cart::instance('purchase')->remove($rowId);
        }
    }

    // add product to purchase cart
    public function sessionStore($id)
    {
        $products = Product::where('id', $id)->first();
        Cart::instance('purchase')->add([
            'id' =>  $products->id,
            'name' => $products->name,
            'qty' => 1,
            'price' => $products->purchase_rate,
            'options' => [
                'code' => $products->code,
                'barcode' => $products->barcode,
                'discount' => 0,
                'weight' => $products->size->name,
                'brand_id' => $products->brand_id,
                'type' => $products->type
            ]
        ]);
    }

    //cancel order
    public function cancel()
    {
        Cart::instance('purchase')->destroy();
        session()->flash('supplier');
        session()->flash('pre_due');
        session()->flash('adv_pay');
        return redirect()->route('live.purchase.create');
    }

    //warehouse search
    public function warehouseSearch($value)
    {
        $this->warehouse_id = $value;
        $this->warehouse_name = Warehouse::find($this->warehouse_id)->name;
    }
    // store supplier info into session
    public function supplierInfo()
    {
        $validateData = $this->validate();
        // dd($validateData);
        $store_name = Store::find($validateData['product_store_id'])->name;
        $supplier = session()->get('supplier');
        if (!$supplier) {
            $supplier = [
                'supplier_id' => $this->supplier_id,
                'supplier_name' => $this->supplier_name,
                'address' => $this->address,
                'mobile' =>  $this->mobile,
                'balance' => $this->balance,
                'date' => $this->full_date,
                'warehouse_id' => $validateData['warehouse_id'],
                'product_store_id' => $validateData['product_store_id'],
                'product_store_name' => $store_name,
                'warehouse_name' => $this->warehouse_name,
                'supplier_remarks' => $this->supplier_remarks,
                'transport_no' => $validateData['transport_no'],
                'delivery_man' => $validateData['delivery_man'],
            ];

            session()->put('supplier',  $supplier);
        } else {

            if (!$supplier) {
                $supplier = [
                    $this->supplier_id => [
                        'supplier_id' => $this->supplier_id,
                        'supplier_name' => $this->supplier_name,
                        'address' => $this->address,
                        'mobile' =>  $this->mobile,
                        'balance' => $this->balance,
                        'date' => $this->full_date,
                        'warehouse_id' => $validateData['warehouse_id'],
                        'product_store_id' => $validateData['product_store_id'],
                        'product_store_name' => $store_name,
                        'warehouse_name' => $this->warehouse_name,
                        'supplier_remarks' => $this->supplier_remarks,
                        'transport_no' => $validateData['transport_no'],
                        'delivery_man' => $validateData['delivery_man'],
                    ]
                ];

                session()->put('supplier',  $supplier);
            }
        }
        return redirect()->route('live.purchase.checkout');
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
            $this->balance = $this->get_previous_balance($suppliers->id, $this->full_date);

            $this->address = $suppliers->address;
            $this->mobile = $suppliers->mobile;
            $this->supplier_id = $suppliers->id;
            session()->put('balance', $suppliers->balance);
        }


        if (!empty($this->brand_id)) {

            $products_grid = Product::where('brand_id', $this->brand_id)
                ->orderBy('code', 'asc')
                ->get();
            if ($this->brand_id == '0') {
                $products_grid = Product::latest()->orderBy('code', 'asc')->limit(18)->get();
            }
        } else {
            if (!empty($this->new_search)) {
                $this->searches = Product::where('name', 'Like', "%{$this->new_search}%")
                    ->orWhere('code', 'Like', "%{$this->new_search}%")
                    ->orderBy('code', 'asc')
                    ->limit(9)->get();
            } else {
                $this->searches = 0;
            }
            $products_grid = Product::where('name', 'Like', "%{$this->search}%")
                ->orWhere('code', 'Like', "%{$this->search}%")
                ->limit(18)
                ->orderBy('code', 'asc')
                ->get();
        }
        $products = Product::latest()
            ->orderBy('code', 'asc')
            ->get();

        $all_stocks = ProductStore::get();
        $store_stocks = $all_stocks->groupBy('product_id')->map(function ($items) {
            return [
                'name' => $items->first()->product->name,
                'code' => $items->first()->product->code,
                'qty' => $items->sum('product_quantity'),
                'type' => $items->first()->product->type,
                'price' => $items->last()->purchase_price
            ];
        });
        // dd($store_stocks);
        // if ($this->source_store_id) {
        // }


        $stores = Store::where('status', 1)->get();
        $suppliers = Supplier::get();
        $warehouses = Warehouse::where('status', 1)->get();
        $brands = Brand::get();
        return view('livewire.purchase.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
