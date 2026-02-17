<?php

namespace App\Livewire\Purchase;

use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseList extends Component
{
    use WithPagination;
    public $view = 'v1';

    #[Url(as: 'perpage')]
    public $perPage;

    public $start_date;

    public $end_date;

    public function mount($view)
    {
        $this->view = $view;
        $this->perPage = $this->perPage ?? 10;
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function rules()
    {
        return [
            'start_date'    => 'required|date',
            'end_date'      => 'required|date'
        ];
    }

    public function search()
    {
        $this->resetPage();
    }

    public function searchReset()
    {
        $this->start_date = null;
        $this->end_date = null;
    }

    public function render()
    {
        if ($this->start_date != null && $this->end_date != null) {
            $query = SupplierLedger::where('type', 'purchase')
                ->whereBetween('date', [$this->start_date, $this->end_date])
                ->orderBy('date', 'DESC');
        } else {
            $query = SupplierLedger::where('type', 'purchase')
                ->orderBy('date', 'DESC');
        }

        if ($this->perPage === 'all') {
            $supplier_ledger = $query->get(); // Fetch all records
        } else {
            $supplier_ledger = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        $ledgerIds = $supplier_ledger->pluck('id')->toArray();
        $products = SupplierTransactionDetails::whereIn('transaction_id', $ledgerIds)
            ->orderBy('id', 'DESC')
            ->get()
            ->groupBy('transaction_id');

        return view('livewire.purchase.purchase-list-' . $this->view, get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
