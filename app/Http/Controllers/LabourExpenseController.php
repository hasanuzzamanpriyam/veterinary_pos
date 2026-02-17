<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class LabourExpenseController extends Controller
{
    public function index()
    {
        $labour_expenses = Expense::where('expense_type', 'labour_expense')->latest()->get();
        return view('admin.expense.labour.index',get_defined_vars());
    }

    public function create()
    {
        return view('admin.expense.labour.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'date' => [ 'required','max:10'],
            'voucher_no' => ['max:10'],
            'purpose' => ['max:255'],
            'amount' => ['required','max:10'],
            'receiver_name' => ['required','max:100'],
            'payment_amount' => ['required','max:100'],
            'payment_by' => ['required','max:100'],
            'remarks' => ['max:1000'],

        ], [
            'payment_by.required' => 'The Paying By is required.',
        ]);

        // generate invoice number
        $voucher_no = Expense::max('voucher_no');
        if(!$voucher_no)
        {
            $voucher_no= 01;
        }
        else
        {
            $voucher_no = $voucher_no+1;
        }

        Expense::insert([
            'date' => $request->date,
            'voucher_no' => $voucher_no,
            'expense_type' => 'labour_expense',
            'purpose' => $request->purpose,
            'amount' => $request->amount,
            'receiver_name' => $request->receiver_name,
            'payment_amount' => $request->payment_amount,
            'payment_by' => $request->payment_by,
            'remarks' => $request->remarks,
            'created_by' => auth()->user()->name,
        ]);

        $alert = array('msg' => 'Labour Expense Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('labour.expense.index')->with($alert);

    }
    public function edit($id)
    {

        $labour_expense = Expense::where('id',$id)->first();

        return view('admin.expense.labour.edit', get_defined_vars());
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'date' => [ 'required','max:10'],
            'voucher_no' => ['required','max:10'],
            'purpose' => ['max:255'],
            'amount' => ['required','max:10'],
            'receiver_name' => ['required','max:100'],
            'payment_amount' => ['required','max:100'],
            'payment_by' => ['required','max:100'],
            'remarks' => ['max:1000'],

        ], [
            'payment_by.required' => 'The Paying By is required.',
        ]);

        Expense::where('id',$request->id)->update([
            'date' => $request->date,
            'voucher_no' => $request->voucher_no,
            'purpose' => $request->purpose,
            'amount' => $request->amount,
            'receiver_name' => $request->receiver_name,
            'payment_amount' => $request->payment_amount,
            'payment_by' => $request->payment_by,
            'remarks' => $request->remarks,

        ]);

        $alert = array('msg' => 'Labour Expense Successfully Update', 'alert-type' => 'info');
        return redirect()->route('labour.expense.index')->with($alert);

    }

    public function delete($id)
    {

        Expense::where('id',$id)->delete();
        $alert = array('msg' => 'Labour Expense Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('labour.expense.index')->with($alert);
    }
}
