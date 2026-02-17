<?php

namespace App\Livewire\Admin\ProductOffers;

use App\Models\Product;
use App\Models\ProductOffer;
use Livewire\Component;

class Create extends Component
{
    public $product_id;
    public $type = 'percentage';
    public $value = 0;
    public $start_date;
    public $end_date;
    public $active = true;

    public function rules()
    {
        return [
            'product_id' => 'nullable|exists:products,id',
            'type' => 'required|in:percentage,amount',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function store()
    {
        $this->validate();

        ProductOffer::create([
            'product_id' => $this->product_id,
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'active' => $this->active,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Offer created successfully');
        return redirect()->route('product.offers.list');
    }

    public function render()
    {
        $products = Product::select('id','name')->orderBy('name')->get();
        return view('livewire.admin.product-offers.create', ['products' => $products]);
    }
}
