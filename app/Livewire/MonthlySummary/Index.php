<?php

namespace App\Livewire\MonthlySummary;

use App\Models\CustomerLedger;
use App\Models\Expense;
use App\Models\SupplierLedger;
use Livewire\Component;

class Index extends Component
{
    public $date;
    public $purchase;
    public $sales;
    public $collection;
    public $payment;
    public $salary;
    public $bank;
    public $labour;
    public $dokan;
    public $carring;
    public $month_name;



    public function mount()
    {
        // Set the initial date when the component is mounted
        $this->date = now()->toDateString();
    }

    public function resetReport()
    {
        $this->reset('sales');
        $this->reset('purchase');
        $this->reset('collection');
        $this->reset('payment');
        $this->reset('salary');
        $this->reset('bank');
        $this->reset('labour');
        $this->reset('dokan');
        $this->reset('carring');
        $this->reset('month_name');
    }


    public function monthlyReportSearch()
    {

        if ($this->date) {

            $this->month_name = date("F-Y", strtotime($this->date));

            $this->sales = CustomerLedger::where('type', 'sale')->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();

            $this->purchase = SupplierLedger::where('type', 'purchase')->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();

            $this->collection = CustomerLedger::where('payment', '>', 0)->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();

            $this->payment = SupplierLedger::where('payment', '>', 0)->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();

            $this->salary = Expense::where('expense_type', 'salary_expense')->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();
            $this->bank = Expense::where('expense_type', 'bank_expense')->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();
            $this->labour = Expense::where('expense_type', 'labour_expense')->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();
            $this->dokan = Expense::where('expense_type', 'dokan_expense')->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();
            $this->carring = Expense::where('expense_type', 'carring_expense')->whereMonth('date',  date('m', strtotime($this->date)))
                ->whereYear('date', date('Y', strtotime($this->date)))
                ->get();
        } else {
        }
    }
    public function render()
    {
        return view('livewire.monthly-summary.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
