<?php

namespace App\Livewire\Employee;

use App\Models\Employee;
use App\Models\EmployeeLedger;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListAll extends Component
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
        $query = Employee::where('name', 'LIKE', "%$this->queryString%")
            ->orWhere('mobile', 'LIKE', "%$this->queryString%")
            ->orWhere('address', 'LIKE', "%$this->queryString%")
            ->orWhere('nid', 'LIKE', "%$this->queryString%")
            ->orWhere('designation', 'LIKE', "%$this->queryString%")
            ->orderBy('created_at', 'asc');

        if ($this->perPage === 'all') {
            $employees = $query->get(); // Fetch all records
        } else {
            $employees = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        $ids = $employees->pluck('id')->toArray();
        $transactions = EmployeeLedger::select('employee_id')
            ->selectRaw("SUM(CASE WHEN type = 'salary' THEN amount ELSE 0 END) as total_salary")
            ->selectRaw("SUM(CASE WHEN type = 'payment' THEN amount ELSE 0 END) as total_payment")
            ->whereIn('employee_id', $ids)
            ->groupBy('employee_id')->get();

        // dd($transactions);
        return view('livewire.employee.list-all', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
