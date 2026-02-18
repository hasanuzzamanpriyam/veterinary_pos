<?php

namespace App\Livewire\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Size;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\ProductOffer;
use Livewire\Component;

class Checkout extends Component
{

    public $product_groups;
    public $categories;
    public $warehouses;
    public $units;
    public $brands;
    public $sizes;
    public $product;
    // Offer fields (only editable by Super Admin)
    public $offer_type = 'percentage';
    public $offer_value = 0;
    public $offer_start_date;
    public $offer_end_date;
    public $offer_active = true;

    public function mount() {
        $this->product_groups = ProductGroup::get();
        $this->categories = Category::get();
        $this->warehouses = Warehouse::get();
        $this->units = Unit::get();
        $this->brands = Brand::get();
        $this->sizes = Size::get();
        $this->product = session()->has('product_data') ? session()->get('product_data') : null;
    }

    public function cancel()
    {
        session()->flash('product_data');
        return redirect()->route('live.product.create');
    }

    public function submit(){
                if(session()->has('product_data')) {
                        $product_data = session()->get('product_data');

                    $product = Product::insertGetId([
                'code' => $product_data['code'],
                'name' => $product_data['name'],
                'brand_id' => $product_data['brand_id'],
                'category_id' => $product_data['category_id'],
                'type' => $product_data['type'],
                'size_id' => $product_data['size_id'],
                'sku' => $product_data['sku'],
                'alert_expire_date' => $product_data['alert_expire_date'],
                'barcode' => $product_data['barcode'],
                'group_id' => $product_data['group_id'],
                'purchase_rate' => $product_data['purchase_rate'],
                'price_rate' => $product_data['price_rate'],
                'mrp_rate' => $product_data['mrp_rate'],
                'alert_quantity' => $product_data['alert_quantity'],
                'remarks' => $product_data['remarks'],
                'photo' => $product_data['photo'],

            ]);

            // If current user is Super Admin and provided offer, create ProductOffer
            if (auth()->check() && auth()->user()->hasRole('Super Admin')) {
                $value = (float) $this->offer_value;
                if ($value > 0) {
                    ProductOffer::create([
                        'product_id' => $product,
                        'type' => $this->offer_type,
                        'value' => $value,
                        'start_date' => $this->offer_start_date ?: null,
                        'end_date' => $this->offer_end_date ?: null,
                        'active' => (bool) $this->offer_active,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            session()->forget('product_data');

            $alert = array('msg' => 'Product Successfully Inserted', 'alert-type' => 'success');
            return redirect()->route('product.index')->with($alert);

        }
    }

    public function render()
    {
        return view('livewire.product.checkout', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
