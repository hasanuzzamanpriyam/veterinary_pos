<?php

namespace App\Livewire\PaymentReport;

use App\Models\Supplier;
use App\Models\SupplierLedger;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Index extends Component
{
    public $start_date;
    public $end_date;
    public $get_supplier_id;
    public $paying_by;
    public $total_amount;
    public $total_quantity;
    public $total_weight;
    public $total_ton;
    public $reports;

    public function resetSupplier()
    {
        $this->reset('reports');
        $this->reset('start_date');
        $this->reset('end_date');
        $this->reset('paying_by');
        $this->reset('get_supplier_id');
    }

    public function paymentReportSearch()
    {
        $this->reports = SupplierLedger::query()
            ->when(isset($this->start_date), function (Builder $query) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($this->start_date)));
            })
            ->when(isset($this->end_date), function (Builder $query) {
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($this->end_date)));
            })
            ->when(isset($this->get_supplier_id), function (Builder $query) {
                $query->where('supplier_id', $this->get_supplier_id);
            })
            ->when(isset($this->paying_by) && $this->paying_by != 'all', function (Builder $query) {
                $query->where('payment_by', $this->paying_by);
            })
            ->get();

    }


    public function render()
    {
        $suppliers = Supplier::get();
        // $this->paymentReportSearch();
        return view('livewire.payment-report.index', get_defined_vars());
    }
}
