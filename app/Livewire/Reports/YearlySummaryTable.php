<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class YearlySummaryTable extends Component
{

    #[Reactive]
    public $summary;

    public function render()
    {
        return view('livewire.reports.yearly-summary-table');
    }
}
