<?php

namespace App\Livewire\Expense;

use App\Models\ExpenseCategory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
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
        $query = ExpenseCategory::where('name', 'LIKE', "%$this->queryString%")
            ->orderBy('name', 'asc');

        if ($this->perPage === 'all') {
            $expense_categories = $query->get(); // Fetch all records
        } else {
            $expense_categories = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        return view('livewire.expense.category-list', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
