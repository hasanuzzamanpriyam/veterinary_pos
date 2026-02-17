<?php

namespace App\Livewire\CollectionReport;

use App\Models\Collection;
use App\Models\customer;
use App\Models\CustomerLedger;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Index extends Component
{
    public $start_date;
    public $end_date;
    public $get_customer_id;
    public $received_by;
    public $total_amount;
    public $total_quantity;
    public $total_weight;
    public $total_ton;
    public $reports;

    public function resetCustomer()
    {
        $this->reset('reports');
        $this->reset('start_date');
        $this->reset('end_date');
        $this->reset('received_by');
        $this->reset('get_customer_id');
    }

    public function collectionReportSearch()
    {
        $this->reports = CustomerLedger::query()
            ->when(isset($this->start_date), function (Builder $query) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($this->start_date)));
            })
            ->when(isset($this->end_date), function (Builder $query) {
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($this->end_date)));
            })
            ->when(isset($this->get_customer_id), function (Builder $query) {
                $query->where('customer_id', $this->get_customer_id);
            })
            ->when(isset($this->received_by) && $this->received_by != 'all', function (Builder $query) {
                $query->where('payment_by', $this->received_by);
            })
            ->get();
    }


    public function render()
    {

        $customers = customer::get();
        return view('livewire.collection-report.index', get_defined_vars());
    }
}
