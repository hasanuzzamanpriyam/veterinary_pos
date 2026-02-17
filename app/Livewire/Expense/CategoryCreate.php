<?php

namespace App\Livewire\Expense;

use App\Models\ExpenseCategory;
use Livewire\Component;

class CategoryCreate extends Component
{
    public $name;
    public $description;

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'max:1000',
        ];
    }
    public function create()
    {
        $this->validate();

        ExpenseCategory::insert([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $alert = array('msg' => 'Expense Category Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('expense_category.index')->with($alert);
    }
    public function render()
    {
        return view('livewire.expense.category-create', get_defined_vars())
            ->extends('layouts.admin')
            ->section('main-content');
    }
}
