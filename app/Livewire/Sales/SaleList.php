<?php

namespace App\Livewire\Sales;

use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SaleList extends Component
{
    use WithPagination;
    public $view = 'v1';

    #[Url(as: 'perpage')]
    public $perPage;
    public $start_date;
    public $end_date;
    public $rw_start_date;
    public $rw_end_date;

    public function mount($view = 'v1')
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
        $this->start_date = date('Y-m-d', strtotime($this->rw_start_date));
        $this->end_date = date('Y-m-d', strtotime($this->rw_end_date));
        $this->resetPage();
    }

    public function searchReset()
    {
        $this->start_date = null;
        $this->end_date = null;
        $this->rw_start_date = null;
        $this->rw_end_date = null;
    }

    public function render()
    {
        if ($this->start_date != null && $this->end_date != null) {
            $query = CustomerLedger::where('type', 'sale')
                ->whereBetween('date', [$this->start_date, $this->end_date])
                ->orderBy('date', 'DESC');
        } else {
            $query = CustomerLedger::where('type', 'sale')
                ->orderBy('date', 'DESC');
        }

        if ($this->perPage === 'all') {
            $customer_ledger = $query->get(); // Fetch all records
        } else {
            $customer_ledger = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        $ledgerIds = $customer_ledger->pluck('id')->toArray();
        $products = CustomerTransactionDetails::whereIn('transaction_id', $ledgerIds)
            ->orderBy('id', 'DESC')
            ->get()
            ->groupBy('transaction_id');

        return view('livewire.sales.sale-list-' . $this->view, get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
