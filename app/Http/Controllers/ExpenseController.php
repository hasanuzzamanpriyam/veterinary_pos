<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ExpenseController extends Controller
{

    public function delete($id)
    {

        Expense::where('id', $id)->delete();

        $alert = array('msg' => 'Expense Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('expense.index')->with($alert);
    }

    public function report()
    {
        return view('admin.expense.report.report');
    }

    public function expenseReportDownload($start_date, $end_date, $get_expense)
    {
        if ($get_expense == 1 && ($start_date && $end_date)) {

            $salaries = Expense::where('expense_type', 'salary_expense')->whereBetween('date', [$start_date, $end_date])->get();
            $expense_name = 'Salary';

            $pdf = PDF::loadView('admin.report.pdf.expense', ['start_date' => $start_date, 'end_date' => $end_date, 'salaries' => $salaries, 'expense_name' => $expense_name]);
            $date = now()->format('d-m-Y');
            return $pdf->stream($expense_name . '_expense_report(' . $date . ').pdf');
        } elseif ($get_expense == 2 && ($start_date && $end_date)) {
            $banks = Expense::where('expense_type', 'bank_expense')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $expense_name = 'Bank';
            $pdf = PDF::loadView('admin.report.pdf.expense', ['start_date' => $start_date, 'end_date' => $end_date, 'banks' => $banks, 'expense_name' => $expense_name]);
            $date = now()->format('d-m-Y');
            return $pdf->stream($expense_name . '_expense_report(' . $date . ').pdf');
        } elseif ($get_expense == 3 && ($start_date && $end_date)) {
            $labours = Expense::where('expense_type', 'labour_expense')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $expense_name = 'Labour';

            $pdf = PDF::loadView('admin.report.pdf.expense', ['start_date' => $start_date, 'end_date' => $end_date, 'labours' => $labours, 'expense_name' => $expense_name]);
            $date = now()->format('d-m-Y');
            return $pdf->stream($expense_name . '_expense_report(' . $date . ').pdf');
        } elseif ($get_expense == 4 && ($start_date && $end_date)) {
            $carrings = Expense::where('expense_type', 'carring_expense')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $expense_name = 'Carring';

            $pdf = PDF::loadView('admin.report.pdf.expense', ['start_date' => $start_date, 'end_date' => $end_date, 'carrings' => $carrings, 'expense_name' => $expense_name]);
            $date = now()->format('d-m-Y');
            return $pdf->stream($expense_name . '_expense_report(' . $date . ').pdf');
        } elseif ($get_expense == 5 && ($start_date && $end_date)) {
            $dokans = Expense::where('expense_type', 'dokan_expense')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $expense_name = 'Dokan';

            $pdf = PDF::loadView('admin.report.pdf.expense', ['start_date' => $start_date, 'end_date' => $end_date, 'dokans' => $dokans, 'expense_name' => $expense_name]);
            $date = now()->format('d-m-Y');
            return $pdf->stream($expense_name . '_expense_report(' . $date . ').pdf');
        } else {
        }
    }
}
