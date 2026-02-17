<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLedger;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryExpenseController extends Controller
{
    public function index()
    {
        $perpage = 10;
        $salary_expenses = Expense::join('employees','expenses.employee_id','=','employees.id')
            ->select('expenses.*','employees.name','employees.mobile','employees.address','employees.designation')
            ->where('expenses.expense_type', 'salary_expense')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perpage);
        return view('admin.expense.salary.index', get_defined_vars());
    }

    public function create()
    {
       $employees = Employee::get();
        return view('admin.expense.salary.create', compact('employees'));
    }

    public function get_single_employee($id)
    {
       $dbData = Employee::where('id', $id)->first();
        return response($dbData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_no' => [ 'required','max:255'],
            'amount' => [ 'required','max:10'],
            'other_charge' => ['nullable','max:10'],
            'remarks' => ['max:1000'],

        ], [
            'id_no.required' => 'The Name is required.',
            'amount.required' => 'The Salary Amount is required.'
        ]);

        DB::transaction(function() use ($request) {

            $amount = empty($request->amount) ? 0 : $request->amount;
            $other_charge = empty($request->other_charge) ? 0 : $request->other_charge;
            $date = date('Y-m-d', strtotime($request->date));
            $id = Expense::insertGetId([
                'expense_type'  => 'salary_expense',
                'employee_id'   => $request->id_no,
                'amount'        => empty($request->amount) ? 0 : $request->amount,
                'other_charge'  => empty($request->other_charge) ? 0 : $request->other_charge,
                'remarks'       => $request->remarks,
                'date'          => $date,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            Employee::where('id', $request->id_no)->increment('balance', ($amount + $other_charge));

            EmployeeLedger::insert([
                'expense_id' => $id,
                'type' => 'salary',
                'employee_id' => $request->id_no,
                'amount' => (float)$amount + (float)$other_charge,
                'remarks' => $request->remarks,
                'date' => $date,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });




        $alert = array('msg' => 'Salary Expense Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('salary.expense.index')->with($alert);


    }
    public function edit($id)
    {
        $salary_expense = Expense::join('employees','expenses.employee_id','=','employees.id')
            ->select('expenses.*','employees.name','employees.mobile','employees.address','employees.designation')
            ->findOrFail($id);

        return view('admin.expense.salary.edit', get_defined_vars());
    }

    public static function cleanNumber( $amount )
    {
        $clean_number = preg_replace("/[^0-9.]/", "", $amount);
        return $clean_number;
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required','max:10'],
            'amount' => [ 'required','max:10'],
            'other_charge' => [ 'max:10'],
            'remarks' => ['max:1000'],
        ], [
            'amount.required' => 'The Salary Amount is required.'
        ]);

        DB::transaction(function() use ($request) {
            $amount = empty($request->amount) ? 0 : $request->amount;
            $other_charge = empty($request->other_charge) ? 0 : $request->other_charge;

            // Get old expense
            $expense_invoice = Expense::where('id', $request->id)->first();

            Expense::where('id', $request->id)->update([
                'amount' => self::cleanNumber($request->amount),
                'other_charge' => self::cleanNumber($request->other_charge),
                'remarks' => $request->remarks,
                'updated_at' => now()
            ]);



            // need to update employee balance
            Employee::where('id', $expense_invoice->employee_id)->decrement('balance', ($expense_invoice->amount + $expense_invoice->other_charge));
            Employee::where('id', $expense_invoice->employee_id)->increment('balance', ($amount + $other_charge));

            EmployeeLedger::where('expense_id', $request->id)->update([
                'amount' => ($amount + $other_charge),
                'remarks' => $request->remarks,
                'updated_at'    => now()
            ]);
        });

        $alert = array('msg' => 'Salary Expense Successfully Update', 'alert-type' => 'info');
        return redirect()->route('salary.expense.index')->with($alert);


    }

    public function delete($id)
    {
        if($id){
            $invoice = Expense::where('id', $id)->first();
            Employee::where('id', $invoice->employee_id)->decrement('balance', ($invoice->amount + $invoice->other_charge));
            EmployeeLedger::where('expense_id', $id)->delete();
            $invoice->delete();
            $alert = array('msg' => 'Salary Expense Successfully Deleted', 'alert-type' => 'warning');
            return redirect()->route('salary.expense.index')->with($alert);
        }
    }
}
