<?php

namespace App\Livewire\Expense;

use App\Models\ExpenseCategory;
use Livewire\Component;

class CategoryEdit extends Component
{
    public $id;
    public $name;
    public $description;

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'max:1000',
        ];
    }

    public function mount($id)
    {
        $this->id = $id;
        $category = ExpenseCategory::where('id', $id)->first();
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function update()
    {
        $this->validate();

        ExpenseCategory::where('id', $this->id)->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $alert = array('msg' => 'Expense Category Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('expense_category.index')->with($alert);
    }

    public function render()
    {
        return view('livewire.expense.category-edit', get_defined_vars())
        ->extends('layouts.admin')
        ->section('main-content');
    }
}
