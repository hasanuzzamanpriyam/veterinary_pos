<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Expense;
use Illuminate\Http\Request;

class DokanExpenseController extends Controller
{
    public function index()
    {
        $dokan_expenses = Expense::join('stores','expenses.id_no','=','stores.id')->select('expenses.*','stores.name')->where('expense_type', 'dokan_expense')->latest()->get();
        return view('admin.expense.dokan.index',get_defined_vars());
    }

    public function create()
    {
        $stores = Store::latest()->get();
        return view('admin.expense.dokan.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'date' => [ 'required','max:10'],
            'voucher_no' => ['max:10'],
            'id_no' => ['required','max:10'],
            'amount' => ['required','max:10'],
            'amount_month' => ['required','max:100'],
            'year' => ['required','max:10'],
            'payment_amount' => ['required','max:100'],
            'receiving_by' => ['max:100'],
            'payment_by' => ['required','max:100'],
            'remarks' => ['max:1000'],

        ], [
            'id_no.required' => 'The Dokan Name is required.',
            'amount.required' => 'The Rent Amount is required.',
            'amount_month.required' => 'The Rent Month is required.',
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
            'expense_type' => 'dokan_expense',
            'id_no' => $request->id_no,
            'amount' => $request->amount,
            'amount_month' => $request->amount_month,
            'year' => $request->year,
            'payment_by' => $request->payment_by,
            'receiving_by' => $request->receiving_by,
            'payment_amount' => $request->payment_amount,
            'remarks' => $request->remarks,
            'created_by' => auth()->user()->name,
        ]);

        $alert = array('msg' => 'Dokan Expense Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('dokan.expense.index')->with($alert);

    }
    public function edit($id)
    {
        $stores = Store::latest()->get();
        $dokan_expense = Expense::join('stores','expenses.id_no','=','stores.id')->select('expenses.*','stores.name')->findOrFail($id);

        return view('admin.expense.dokan.edit', get_defined_vars());
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'date' => [ 'required','max:10'],
            'voucher_no' => ['required','max:10'],
            'id_no' => ['required','max:10'],
            'amount' => ['required','max:10'],
            'amount_month' => ['required','max:100'],
            'year' => ['required','max:10'],
            'payment_amount' => ['required','max:100'],
            'receiving_by' => ['max:100'],
            'payment_by' => ['required','max:100'],
            'remarks' => ['max:1000'],

        ], [
            'id_no.required' => 'The Dokan Name is required.',
            'amount.required' => 'The Rent Amount is required.',
            'amount_month.required' => 'The Rent Month is required.',
            'payment_by.required' => 'The Paying By is required.',
        ]);

        Expense::where('id',$request->id)->update([
            'date' => $request->date,
            'voucher_no' => $request->voucher_no,
            'id_no' => $request->id_no,
            'amount' => $request->amount,
            'amount_month' => $request->amount_month,
            'year' => $request->year,
            'payment_by' => $request->payment_by,
            'receiving_by' => $request->receiving_by,
            'payment_amount' => $request->payment_amount,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Dokan Expense Successfully Update', 'alert-type' => 'info');
        return redirect()->route('dokan.expense.index')->with($alert);

    }

    public function delete($id)
    {

        Expense::where('id',$id)->delete();
        $alert = array('msg' => 'Dokan Expense Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('dokan.expense.index')->with($alert);
    }
}
