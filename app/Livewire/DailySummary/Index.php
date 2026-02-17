<?php

namespace App\Livewire\DailySummary;

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
    }


    public function dailyReportSearch()
    {

        if ($this->date) {

            // dd($this->date);

            $this->sales = CustomerLedger::where('type', 'sale')->where('date', $this->date)->get();

            $this->purchase = SupplierLedger::where('type', 'purchase')->where('date', $this->date)->get();

            $this->collection = CustomerLedger::where('payment', '>', 0)->where('date', $this->date)->get();


            $this->payment = SupplierLedger::where('payment', '>', 0)->where('date', $this->date)->get();

            $this->salary = Expense::where('expense_type', 'salary_expense')->where('date', $this->date)->get();
            $this->bank = Expense::where('expense_type', 'bank_expense')->where('date', $this->date)->get();
            $this->labour = Expense::where('expense_type', 'labour_expense')->where('date', $this->date)->get();
            $this->dokan = Expense::where('expense_type', 'dokan_expense')->where('date', $this->date)->get();
            $this->carring = Expense::where('expense_type', 'carring_expense')->where('date', $this->date)->get();

            // dd($this->sales);

        } else {
        }
    }
    public function render()
    {
        return view('livewire.daily-summary.index', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
