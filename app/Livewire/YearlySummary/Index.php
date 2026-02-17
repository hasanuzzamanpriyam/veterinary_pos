<?php

namespace App\Livewire\YearlySummary;

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
    public $year;



    // public function mount()
    // {
    //     // Set the initial date when the component is mounted
    //     $this->date = now()->toDateString();

    // }

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
        $this->reset('year');
    }

    public function getYear($value)
    {
        $this->date = $value;
        //dd($this->date);
    }


    public function yearlyReportSearch()
    {


        if ($this->date) {

            // dd($this->date);

            $this->year = $this->date;


            $this->sales = CustomerLedger::where('type', 'sale')->whereYear('date', $this->date)
                ->get();

            $this->purchase = SupplierLedger::where('type', 'purchase')->whereYear('date', $this->date)
                ->get();

            $this->collection = CustomerLedger::where('payment', '>', 0)->whereYear('date', $this->date)
                ->get();

            $this->payment = SupplierLedger::where('payment', '>', 0)->whereYear('date', $this->date)
                ->get();

            $this->salary = Expense::where('expense_type', 'salary_expense')->whereYear('date', $this->date)
                ->get();
            $this->bank = Expense::where('expense_type', 'bank_expense')->whereYear('date', $this->date)
                ->get();
            $this->labour = Expense::where('expense_type', 'labour_expense')->whereYear('date', $this->date)
                ->get();
            $this->dokan = Expense::where('expense_type', 'dokan_expense')->whereYear('date', $this->date)
                ->get();
            $this->carring = Expense::where('expense_type', 'carring_expense')->whereYear('date', $this->date)
                ->get();
        } else {
        }
    }


    public function render()
    {
        return view('livewire.yearly-summary.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
