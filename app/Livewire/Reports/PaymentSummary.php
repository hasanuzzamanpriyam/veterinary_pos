<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentSummary extends Component
{

    #[Reactive]
    public $ledgers;

    public function render()
    {
        return view('livewire.reports.payment-summary');
    }
}
