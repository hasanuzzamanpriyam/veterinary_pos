<?php

namespace App\Livewire\Product;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductGroup;
use App\Models\Size;
use App\Models\Unit;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{

    use WithFileUploads;

    public $product_groups;
    public $categories;
    public $warehouses;
    public $units;
    public $brands;
    public $sizes;
    public $code;
    public $name;
    public $brand_id;
    public $group_id;
    public $size_id;
    public $type;
    public $unit_id;
    public $alert_quantity;
    public $purchase_rate;
    public $mrp_rate;
    public $price_rate;
    public $category_id;
    public $metric_ton;
    public $barcode;
    public $remarks;
    public $photo;

    public function mount() {
        $this->product_groups = ProductGroup::get();
        $this->categories = Category::get();
        $this->warehouses = Warehouse::get();
        // $this->units = Unit::get();
        $this->brands = Brand::get();
        $this->sizes = Size::get();
    }

    public function rules(){
        return [
            'code'  => 'required',
            'name'  => 'required',
            'brand_id' => 'required',
            'group_id' => 'required',
            'size_id' => 'required',
            'type' => 'required',
            'purchase_rate' => 'required',
            'mrp_rate' => 'required',
            'price_rate' => 'required',
            'alert_quantity' => 'required',
            'category_id' => 'nullable',
            'barcode' => 'nullable',
            'remarks' => 'nullable|max:255',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function updatedPhoto(){
        $this->validateOnly('photo', [
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    public function sessionCreate(){
        $validated_data = $this->validate();
        // dd('data', $validated_data);
        if ($this->photo){
            $filename = $this->photo->store('/images/product', 'public');
        }else{
            $filename = "";
        }
        $product = [
            'code' => $validated_data['code'],
            'name' => $validated_data['name'],
            'brand_id' => $validated_data['brand_id'],
            'group_id' => $validated_data['group_id'],
            'size_id' => $validated_data['size_id'],
            'type' => $validated_data['type'],
            'purchase_rate' => $validated_data['purchase_rate'],
            'mrp_rate' => $validated_data['mrp_rate'],
            'price_rate' => $validated_data['price_rate'],
            'alert_quantity' => $validated_data['alert_quantity'],
            'category_id' => $validated_data['category_id'],
            'barcode' => $validated_data['barcode'],
            'remarks' => $validated_data['remarks'],
            'photo' => !empty($filename) ? '/storage/' . $filename : '',
        ];

            session()->put('product_data', $product);

        return redirect()->route('live.product.checkout');
    }

    public function cancel()
    {
        session()->flash('product_data');
        return redirect()->route('live.product.create');
    }

    public function render()
    {

        // dd(session()->has('product_data'));
        if(session()->has('product_data')){
            $product = session()->get('product_data');
            $this->code = isset($product['code']) ? $product['code'] : null;
            $this->name = isset($product['name']) ? $product['name'] : null;
            $this->brand_id = isset($product['brand_id']) ? $product['brand_id'] : null;
            $this->group_id = isset($product['group_id']) ? $product['group_id'] : null;
            $this->size_id = isset($product['size_id']) ? $product['size_id'] : null; //$product['size_id'];
            $this->type = isset($product['type']) ? $product['type'] : null; // $$product['type'];
            $this->unit_id = isset($product['unit_id']) ? $product['unit_id'] : null; //$product['unit_id'];
            $this->purchase_rate = isset($product['purchase_rate']) ? $product['purchase_rate'] : null; //$product['purchase_rate'];
            $this->mrp_rate = isset($product['mrp_rate']) ? $product['mrp_rate'] : null; //$product['mrp_rate'];
            $this->price_rate = isset($product['price_rate']) ? $product['price_rate'] : null; //$product['price_rate'];
            $this->alert_quantity = isset($product['alert_quantity']) ? $product['alert_quantity'] : null; //$product['alert_quantity'];
            $this->category_id = isset($product['category_id']) ? $product['category_id'] : null; //$product['category_id'];
            $this->metric_ton = isset($product['metric_ton']) ? $product['metric_ton'] : null; //$product['metric_ton'];
            $this->barcode = isset($product['barcode']) ? $product['barcode'] : null; //$product['barcode'];
            $this->remarks = isset($product['remarks']) ? $product['remarks'] : null; //$product['remarks'];
            // $this->photo = isset($product['photo']) ? $product['photo'] : null; //$product['photo'];
        }
        return view('livewire.product.create', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
