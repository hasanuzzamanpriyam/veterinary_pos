<?php

namespace App\Livewire\PaymentReport;

use App\Models\Supplier as ModelsSupplier;
use App\Models\SupplierLedger;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Report')]
class Supplier extends Component
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

    public function mount($supplier_id) {
        $this->get_supplier_id = $supplier_id;
    }

    public function resetSupplier()
    {
        $this->reset('reports');
        $this->reset('start_date');
        $this->reset('end_date');
        $this->reset('paying_by');
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
                $query->where('supplier_id', $this->get_supplier_id)
                    ->where('payment', '>', 0)
                    ->orWhere('carring', '>', 0)
                    ->orWhere('other_charge', '>', 0)
                    ->orWhere('vat', '>', 0);
            })
            ->when(isset($this->paying_by) && $this->paying_by != 'all', function (Builder $query) {
                $query->where('payment_by', $this->paying_by);
            })
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->dispatch('dataUpdated');

    }

    public function render()
    {
        $this->paymentReportSearch();
        $supplier = ModelsSupplier::where('id', $this->get_supplier_id)->first();
        return view('livewire.payment-report.supplier', get_defined_vars());
    }
}
