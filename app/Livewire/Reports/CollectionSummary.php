<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class CollectionSummary extends Component
{

    #[Reactive]
    public $ledgers;

    public function render()
    {
        return view('livewire.reports.collection-summary');
    }
}
