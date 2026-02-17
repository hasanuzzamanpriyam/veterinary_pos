<?php

namespace App\Livewire\ExpenseReport;



use App\Models\Expense;
use Livewire\Component;

class Index extends Component
{
    public $start_date;
    public $end_date;
    public $get_expense;
    public $expense_name;

    public $banks;
    public $salaries;
    public $labours;
    public $carrings;
    public $dokans;
    public $all_reports;

    public function resetExpenses()
    {
        $this->reset('banks');
        $this->reset('salaries');
        $this->reset('labours');
        $this->reset('carrings');
        $this->reset('dokans');
        $this->reset('expense_name');
        $this->reset('all_reports');
    }


    public function expenseReportSearch()
    {
       // dd($this->get_expense);

        if($this->get_expense == 1 && ($this->start_date && $this->end_date)) {
            $this->salaries = Expense::whereBetween('date',[$this->start_date, $this->end_date])->get();
            $this->expense_name = 'Salary';
        }

    }


    public function render()
    {

        return view('livewire.expense-report.index', get_defined_vars());
    }
}
