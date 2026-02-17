<?php

namespace App\Livewire\CollectionReport;

use App\Models\customer as ModelsCustomer;
use App\Models\CustomerLedger;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Customer extends Component
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

    public function mount($customer_id) {
        $this->get_customer_id = $customer_id;
    }

    public function resetSupplier()
    {
        $this->reset('reports');
        $this->reset('start_date');
        $this->reset('end_date');
        $this->reset('received_by');
    }

    public function collectionReportSearch()
    {
        $this->reports = CustomerLedger::query()
            ->where('payment', '!=', null)
            ->when(isset($this->start_date), function (Builder $query) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($this->start_date)));
            })
            ->when(isset($this->end_date), function (Builder $query) {
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($this->end_date)));
            })
            ->when(isset($this->get_customer_id), function (Builder $query) {
                $query->where('customer_id', $this->get_customer_id)->where('payment', '>', 0);
            })
            ->when(isset($this->received_by) && $this->received_by != 'all', function (Builder $query) {
                $query->where('payment_by', $this->received_by);
            })
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->dispatch('dataUpdated');

    }

    public function render()
    {
        $this->collectionReportSearch();
        $customer = ModelsCustomer::where('id', $this->get_customer_id)->first();
        return view('livewire.collection-report.customer', get_defined_vars());
    }
}
