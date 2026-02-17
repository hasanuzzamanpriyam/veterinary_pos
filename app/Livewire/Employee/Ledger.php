<?php

namespace App\Livewire\Employee;

use App\Models\Employee;
use App\Models\EmployeeLedger;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Ledger extends Component
{
    use WithPagination;
    #[Url(as: 'perpage')]
    public $perPage;

    public $id;
    public $end_date = null;
    public $start_date = null;
    public $payment_method = null;

    public function mount(Request $request, $id){
        $queryParams = $request->query->all();
        $this->id = $id;
        $this->perPage = $this->perPage ?? 10;

    }

    public function updatePerPage($value)
    {
        $this->perPage = $value;
        $this->resetPage(); // Reset to the first page when perPage changes
    }

    public function search(){
        $this->resetPage();
    }

    public function searchReset()
    {
        $this->start_date = null;
        $this->end_date = null;
        $this->payment_method = null;
        $this->perPage = 10;
    }

    public function render()
    {
        $employee = Employee::where('id', $this->id)->first();

        $query = EmployeeLedger::query()
            ->where('employee_id', $this->id)
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC');

        if($this->start_date && $this->end_date){
            $query->whereBetween('date', [
                date('Y-m-d', strtotime($this->start_date)),
                date('Y-m-d', strtotime($this->end_date))
            ]);
        }

        if($this->payment_method){
            $query->where('payment_by', $this->payment_method);
        }

        if ($this->perPage === 'all') {
            $expenses = $query->get(); // Fetch all records
        } else {
            $expenses = $query->paginate((int) $this->perPage); // Paginate based on the dropdown value
        }

        return view('livewire.employee.ledger', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
