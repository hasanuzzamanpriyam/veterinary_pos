<?php

namespace App\Livewire\Expense;

use App\Models\Employee;
use App\Models\Expense;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseList extends Component
{
    use WithPagination;

    #[Url(as: 'perpage')]
    public $perPage;
    public $queryString;
    public $startDate;
    public $endDate;

    public function mount() {

        $this->perPage = $this->perPage ?? 10;
        $this->queryString = '';
    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function supplierOfferSearch()
    {
        $this->resetPage();
    }

    public function resetData()
    {
        $this->queryString = null;
        $this->startDate = null;
        $this->endDate = null;
        $this->perPage = 10;
        $this->resetPage();
    }

    public function updateQueryString()
    {
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function render()
    {
        $query = Expense::query()
        ->when($this->startDate && $this->endDate, function ($query) {
            $query->whereBetween('date', [
                date('Y-m-d', strtotime($this->startDate)),
                date('Y-m-d', strtotime($this->endDate))
            ]);
        })
        ->when($this->queryString, function ($query) {
            $query->where(function($q) {
                $q->where('expense_type', 'LIKE', "%{$this->queryString}%")
                ->orWhere('purpose', 'LIKE', "%{$this->queryString}%")
                ->orWhere('paying_by', 'LIKE', "%{$this->queryString}%")
                ->orWhere('remarks', 'LIKE', "%{$this->queryString}%");
            });
        })
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc');

        if ($this->perPage === 'all') {
            $expense_lists = $query->get(); // Fetch all records
        } else {
            $expense_lists = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        // Get only those employees who is included in the expense list
        $employee_ids = $expense_lists->pluck('employee_id')->toArray();
        $employees = Employee::whereIn('id', $employee_ids)->get();

        return view('livewire.expense.expense-list', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
