<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class CarringExpenseController extends Controller
{
    public function index()
    {
        $carring_expenses = Expense::where('expense_type', 'carring_expense')->latest()->get();
        return view('admin.expense.carring.index',get_defined_vars());
    }

    public function create()
    {
        return view('admin.expense.carring.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'date' => [ 'required','max:10'],
            'voucher_no' => ['max:10'],
            'gary_number' => ['max:255'],
            'load_point' => ['required','max:100'],
            'delivery_point' => ['required','max:100'],
            'driver_name' => ['required','max:100'],
            'payment_amount' => ['required','max:100'],
            'remarks' => ['max:1000'],

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
            'expense_type' => 'carring_expense',
            'gary_number' => $request->gary_number,
            'load_point' => $request->load_point,
            'delivery_point' => $request->delivery_point,
            'driver_name' => $request->driver_name,
            'payment_amount' => $request->payment_amount,
            'remarks' => $request->remarks,
            'created_by' => auth()->user()->name,
        ]);

        $alert = array('msg' => 'Carring Expense Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('carring.expense.index')->with($alert);

    }
    public function edit($id)
    {

        $carring_expense = Expense::where('id',$id)->first();

        return view('admin.expense.carring.edit', get_defined_vars());
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'date' => [ 'required','max:10'],
            'voucher_no' => ['required','max:10'],
            'gary_number' => ['max:255'],
            'load_point' => ['required','max:100'],
            'delivery_point' => ['required','max:100'],
            'driver_name' => ['required','max:100'],
            'payment_amount' => ['required','max:100'],
            'remarks' => ['max:1000'],

        ]);

        Expense::where('id',$request->id)->update([
            'date' => $request->date,
            'voucher_no' => $request->voucher_no,
            'gary_number' => $request->gary_number,
            'load_point' => $request->load_point,
            'delivery_point' => $request->delivery_point,
            'driver_name' => $request->driver_name,
            'payment_amount' => $request->payment_amount,
            'remarks' => $request->remarks,

        ]);

        $alert = array('msg' => 'Carring Expense Successfully Update', 'alert-type' => 'info');
        return redirect()->route('carring.expense.index')->with($alert);

    }

    public function delete($id)
    {

        Expense::where('id',$id)->delete();
        $alert = array('msg' => 'Carring Expense Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('carring.expense.index')->with($alert);
    }
}
