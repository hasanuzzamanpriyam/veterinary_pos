<?php

namespace App\Livewire\CashMaintenance;

use App\Models\CashManager;
use App\Models\CashTransactions;
use Livewire\Component;

class CashView extends Component
{
    public $summary;
    public $cash_data;

    public function mount($id)
    {
        $this->summary = CashManager::where('id', $id)->first();
        $this->cash_data = CashTransactions::where('trnx_id', $id)->get();
    }

    public function render()
    {
        return view('livewire.cash-maintenance.cash-view', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
