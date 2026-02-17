<?php

namespace App\Livewire\Employee;

use App\Models\EmployeeLedger;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AllPaymentsList extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage;
    public $queryString;

    public function mount() {

        $this->perPage = $this->perPage ?? 10;
        $this->queryString = '';
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }
    public function updateQueryString()
    {
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function render()
    {
        $query = EmployeeLedger::query()
            ->where('type', 'payment')
            ->orderBy('created_at', 'desc');

        if ($this->perPage === 'all') {
            $ledgers = $query->get(); // Fetch all records
        } else {
            $ledgers = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }
        // dd($ledgers[0]->employee->name);
        return view('livewire.employee.all-payments-list', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
