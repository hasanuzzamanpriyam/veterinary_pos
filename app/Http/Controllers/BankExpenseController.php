<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Expense;
use Illuminate\Http\Request;

class BankExpenseController extends Controller
{
    public function index()
    {
        $bank_expenses = Expense::join('banks','expenses.id_no','=','banks.id')->select('expenses.*','banks.name')->where('expenses.expense_type', 'bank_expense')->latest()->get();
        return view('admin.expense.bank.index',get_defined_vars());
    }

    public function create()
    {
        $banks = Bank::get();
        return view('admin.expense.bank.create',compact('banks'));
    }

    public function store(Request $request)
    {

        $validator = $request->validate([
            'date' => ['required', 'max:10'],
            'voucher_no' => ['max:10'],
            'id_no' => ['required', 'max:11'],
            'amount' => ['required', 'max:100'],
            'amount_month' => ['required', 'max:100'],
            'year' => ['required', 'max:10'],
            'other_charge' => ['max:100'],
            'payment_amount' => ['required', 'max:100'],
            'payment_by' => ['required', 'max:100'],
            'remarks' => ['max:1000'],
        ], [
            'id_no.required' => 'The Bank Name is required.',
            'amount.required' => 'The Profit Amount is required.',
            'amount_month.required' => 'The Profit Month is required.',
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
            'expense_type' => 'bank_expense',
            'id_no' => $request->id_no,
            'amount' => $request->amount,
            'amount_month' => $request->amount_month,
            'year' => $request->year,
            'other_charge' => $request->other_charge,
            'payment_amount' => $request->payment_amount,
            'payment_by' => $request->payment_by,
            'remarks' => $request->remarks,
            'created_by' => auth()->user()->name,
        ]);

        $alert = array('msg' => 'Bank Expense Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('bank.expense.index')->with($alert);

    }

    public function edit($id)
    {
        $banks = Bank::get();
        $bank_expense = Expense::where('id',$id)->first();

        return view('admin.expense.bank.edit', get_defined_vars());
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'date' => ['required', 'max:10'],
            'voucher_no' => ['required', 'max:10'],
            'id_no' => ['required', 'max:11'],
            'amount' => ['required', 'max:100'],
            'amount_month' => ['required', 'max:100'],
            'year' => ['required', 'max:10'],
            'other_charge' => ['max:100'],
            'payment_amount' => ['required', 'max:100'],
            'payment_by' => ['required', 'max:100'],
            'remarks' => ['max:1000'],
        ], [
            'id_no.required' => 'The Bank Name is required.',
            'amount.required' => 'The Profit Amount is required.',
            'amount_month.required' => 'The Profit Month is required.',
            'payment_by.required' => 'The Paying By is required.',
        ]);

        Expense::where('id',$request->id)->update([
            'date' => $request->date,
            'voucher_no' => $request->voucher_no,
            'id_no' => $request->id_no,
            'amount' => $request->amount,
            'amount_month' => $request->amount_month,
            'year' => $request->year,
            'other_charge' => $request->other_charge,
            'payment_amount' => $request->payment_amount,
            'payment_by' => $request->payment_by,
            'remarks' => $request->remarks,
            'created_by' => auth()->user()->name,

        ]);

        $alert = array('msg' => 'Bank Expense Successfully Update', 'alert-type' => 'info');
        return redirect()->route('bank.expense.index')->with($alert);

    }

    public function delete($id)
    {

        Expense::where('id',$id)->delete();
        $alert = array('msg' => 'Bank Expense Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('bank.expense.index')->with($alert);
    }
}
