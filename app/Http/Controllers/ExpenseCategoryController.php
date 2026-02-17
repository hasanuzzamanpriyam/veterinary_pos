<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function delete($id)
    {

        ExpenseCategory::where('id',$id)->delete();

        $alert = array('msg' => 'Expense Category Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('expense_category.index')->with($alert);
    }
}
