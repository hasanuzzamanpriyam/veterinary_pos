<?php

namespace App\Livewire\Reports;

use Livewire\Component;

class MonthSelector extends Component
{
    public $date = null;
    public $currentDate = null;

    public function update()
    {
        $this->dispatch('monthUpdated', date('Y-m-d', strtotime($this->date)));
        $this->currentDate = $this->date;
    }

    public function render()
    {
        return view('livewire.reports.month-selector');
    }
}
