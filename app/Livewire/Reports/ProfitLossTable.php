<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\Attributes\Reactive;

class ProfitLossTable extends Component
{
    #[Reactive]
    public $summary;
    #[Reactive]
    public $stockedPurchase = 0;
    #[Reactive]
    public $purchasedRateTotal = 0;

    public function render()
    {
        // dump($this->summary);
        return view('livewire.reports.profit-loss-table');
    }
}
