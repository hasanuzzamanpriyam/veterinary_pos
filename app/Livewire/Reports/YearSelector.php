<?php

namespace App\Livewire\Reports;

use Livewire\Component;

class YearSelector extends Component
{
    public $startdate = null;
    public $enddate = null;
    public $currentStartDate = null;
    public $currentEndDate = null;

    public function update()
    {
        $this->dispatch('yearUpdated', [
            'startdate' => date('Y-m-d', strtotime($this->startdate)),
            'enddate' => date('Y-m-d', strtotime($this->enddate)),
        ]);
        $this->currentStartDate = $this->startdate;
        $this->currentEndDate = $this->enddate;
    }

    public function render()
    {
        return view('livewire.reports.year-selector');
    }
}
