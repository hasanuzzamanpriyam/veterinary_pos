<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class ExpenseSummary extends Component
{
    #[Reactive]
    public $ledgers;

    public function render()
    {
        return view('livewire.reports.expense-summary');
    }
}
