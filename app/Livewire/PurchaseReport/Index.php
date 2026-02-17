<?php

namespace App\Livewire\PurchaseReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SupplierLedger;
use App\Models\Supplier;

class Index extends Component
{
    use WithPagination;

    public $get_supplier_id;
    public $start_date;
    public $end_date;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        // We load everything in one go: Ledger -> Transactions -> Product details
        $query = SupplierLedger::with([
            'supplier',
            'warehouse',
            'transactions.product'
        ])
            ->where('type', 'purchase');

        // Filter by Supplier
        if ($this->get_supplier_id) {
            $query->where('supplier_id', $this->get_supplier_id);
        }

        // Filter by Date Range
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [
                date('Y-m-d', strtotime($this->start_date)),
                date('Y-m-d', strtotime($this->end_date))
            ]);
        }

        $query->orderBy('date', 'DESC')->orderBy('id', 'DESC');

        // Execute the query
        if ($this->perPage === 'all') {
            $reports = $query->get();
        } else {
            $reports = $query->paginate((int)$this->perPage);
        }

        return view('livewire.purchase-report.index', [
            'reports'   => $reports,
            'suppliers' => Supplier::orderBy('company_name', 'ASC')->get()
        ]);
    }

    // These ensure the search works correctly when you're on page 2 or 3
    public function updatedGetSupplierId()
    {
        $this->resetPage();
    }
    public function updatedStartDate()
    {
        $this->resetPage();
    }
    public function updatedEndDate()
    {
        $this->resetPage();
    }
}
