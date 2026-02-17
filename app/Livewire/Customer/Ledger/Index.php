<?php

namespace App\Livewire\Customer\Ledger;

use App\Models\CustomerLedger;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $collections = CustomerLedger::where('type', 'collection')->orderBy('id', 'DESC')->get();
        return view('livewire.customer.ledger.index', get_defined_vars());
    }
}
