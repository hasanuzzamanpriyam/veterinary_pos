<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class PurchaseSummary extends Component
{
    #[Reactive]
    public $ledgers;

    #[Reactive]
    public $products;

    public function render()
    {
        return view('livewire.reports.purchase-summary');
    }
}
