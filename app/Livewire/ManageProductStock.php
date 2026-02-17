<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductStore;
use App\Models\Store;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
// use Livewire\WithPagination;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class ManageProductStock extends Component
{
    public $search, $brand_id;
    public $date;
    public $product_store_id;
    public $price_group_id;
    public $products;
    public $product_stores;

    public function rules()
    {
        return
            [
                'product_store_id' => 'required',
            ];
    }

    public function messages()
    {
        return [
            'product_store_id'      => 'Please select a store',
        ];
    }

    public function mount()
    {
        $this->date = now()->format('d-m-Y');
    }


    //Increment cart product
    public function updateQuantity($id, $quantities)
    {
        foreach (Cart::instance('manage_stock')->content() as $item) {
            if ($item->id == $id) {
                $item->qty = $quantities;
                // $item->options->stock += $quantities;
            }
        }
    }

    //Increment cart product
    public function updatePrice($id, $update_price)
    {
        foreach (Cart::instance('manage_stock')->content() as $item) {
            if ($item->id == $id) {
                $item->price = $update_price;
            }
        }
    }


    //remove product from cart
    public function itemRemove($rowId)
    {

        $cart = Cart::instance('manage_stock')->content()->where('rowId', $rowId);
        if ($cart->isNotEmpty()) {
            Cart::remove($rowId);
        }
    }

    // add product to sales cart
    public function sessionStore($id)
    {
        $product = Product::where('id', $id)->first();
        $product_stock = $this->product_stores[$id]['qty'] ?? 0;
        Cart::instance('manage_stock')->add([
            'id' =>  $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->purchase_rate,
            'options' => [
                'code' => $product->code,
                'brand_id' => $product->brand_id,
                'discount' => 0,
                'weight' => $product->size->name,
                'stock' => $product_stock,
                'type' => $product->type
            ]
        ]);

        $this->dispatch('cartUpdated');
    }

    public function stockUpdate()
    {
        $validateData = $this->validate();

        $store = Store::find($validateData['product_store_id']);
        $store_data = [
            'id' => $store->id,
            'name' => $store->name,
            'address' => $store->address,
            'mobile' => $store->mobile,
            'email' => $store->email,
            'status' => $store->status,
            'description' => $store->description,
            'remarks' => $store->remarks
        ];
        session()->put('product_store_data', $store_data);
        return redirect()->route('product.stock.checkout');
    }


    //canceal order
    public function cancel()
    {
        Cart::instance('manage_stock')->destroy();
        session()->flash('product_store_data');
        return redirect()->route('product.stock.manage');
    }

    //warehouse search
    public function productSearch($value)
    {
        if ($value == 0) {
        } else {
            $this->product_store_id = $value;
        }
    }


    // brand wise search
    public function brandSearch($value)
    {
        $this->brand_id = $value;
    }


    public function render()
    {
        $stock_list = ProductStore::query()
            ->when(isset($this->product_store_id), function (Builder $query) {
                $query->where('product_store_id', $this->product_store_id);
            })->get();
        // when('product_store_id', $this->product_store_id ?? '')->get();
        $all_products = Product::latest()
            ->orderBy('code', 'asc')
            ->get();
        $mergedProducts = $stock_list->groupBy('product_id')->map(function ($items) {
            return [
                'qty' => $items->sum('product_quantity'),
                'price' => $items->last()->purchase_price
            ];
        });
        $this->product_stores = $mergedProducts;
        $this->products = $all_products;
        if ($this->product_store_id) {
            if ($this->brand_id) {
                $this->products = ProductStore::where('brand_id', $this->brand_id)
                    ->get();
            } elseif ($this->search) {
                $this->products = ProductStore::where('product_name', 'Like', "%{$this->search}%")
                    ->orWhere('product_code', 'Like', "%{$this->search}%")
                    ->get();
            }
        }
        $stores = Store::where('status', 1)->get();
        $brands = Brand::get();
        // if($this->product_store_id){
        // }
        $this->dispatch('dataUpdated');
        return view('livewire.manage-product-stock', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
