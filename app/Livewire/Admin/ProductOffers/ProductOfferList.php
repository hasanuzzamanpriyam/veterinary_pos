<?php

namespace App\Livewire\Admin\ProductOffers;

use App\Models\ProductOffer;
use Livewire\Component;

class ProductOfferList extends Component
{
    public $offers;

    public function mount()
    {
        $this->offers = ProductOffer::with('product')->orderBy('created_at','desc')->get();
    }

    public function render()
    {
        return view('livewire.admin.product-offers.list', ['offers' => $this->offers]);
    }
}

