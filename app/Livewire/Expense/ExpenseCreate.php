<?php

namespace App\Livewire\Expense;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Livewire\Component;

class ExpenseCreate extends Component
{
    public $expense_categories;
    public $date;
    public $expense_category_id;
    public $amount;
    public $remarks;
    public $paying_by;
    public $note;

    public function mount()
    {
        $this->expense_categories = \App\Models\ExpenseCategory::orderBy('name', 'asc')->get();
    }

    public function create()
    {
        $this->validate([
            'date' => 'required',
            'expense_category_id' => 'required',
            'amount' => 'required',
            'paying_by' => 'required',
        ]);

        $expense_category = ExpenseCategory::where('id', $this->expense_category_id)->first();
        Expense::create([
            'expense_category' => $this->expense_category_id,
            'expense_type' => $expense_category->name,
            'amount' => $this->amount,
            'remarks' => $this->remarks,
            'paying_by' => $this->paying_by,
            'purpose' => $this->note,
            'date' => date('Y-m-d', strtotime($this->date)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $notification = array('msg' => 'Expense added successfully', 'alert-type' => 'success');
        return redirect()->route('expense.index')->with($notification);
    }


    public function render()
    {
        return view('livewire.expense.expense-create', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
