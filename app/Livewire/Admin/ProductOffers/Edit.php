<?php

namespace App\Livewire\Admin\ProductOffers;

use App\Models\Product;
use App\Models\ProductOffer;
use Livewire\Component;

class Edit extends Component
{
    public $offer;
    public $product_id;
    public $type;
    public $value;
    public $start_date;
    public $end_date;
    public $active;

    public function mount($id)
    {
        $this->offer = ProductOffer::findOrFail($id);
        $this->product_id = $this->offer->product_id;
        $this->type = $this->offer->type;
        $this->value = (float) $this->offer->value;
        $this->start_date = optional($this->offer->start_date)->format('Y-m-d');
        $this->end_date = optional($this->offer->end_date)->format('Y-m-d');
        $this->active = $this->offer->active;
    }

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

    public function update()
    {
        $this->validate();

        $this->offer->update([
            'product_id' => $this->product_id,
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'active' => $this->active,
        ]);

        session()->flash('message', 'Offer updated');
        return redirect()->route('product.offers.list');
    }

    public function render()
    {
        $products = Product::select('id','name')->orderBy('name')->get();
        return view('livewire.admin.product-offers.edit', ['products' => $products, 'offer' => $this->offer]);
    }
}
