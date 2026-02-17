<?php

namespace App\Http\Controllers;

use App\Models\CustomerLedger;
use App\Models\Expense;
use App\Models\SupplierLedger;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SummaryReportController extends Controller
{
    //daily summary report
    public function dailyReport()
    {
        return view('admin.summary.daily');
    }

    //daily summary pdf report
    public function dailyReportDownload($date)
    {

        if ($date) {
            $sales = CustomerLedger::where('type', 'sale')->where('date', $date)->get();
            $purchase = SupplierLedger::where('type', 'purchase')->where('date', $date)->get();
            $collection = CustomerLedger::where('payment', '>', 0)->where('date', $date)->get();

            $payment = SupplierLedger::where('payment', '>', 0)->where('date', $date)->get();

            $salary = Expense::where('expense_type', 'salary_expense')->where('date', $date)->get();
            $bank = Expense::where('expense_type', 'bank_expense')->where('date', $date)->get();
            $labour = Expense::where('expense_type', 'labour_expense')->where('date', $date)->get();
            $dokan = Expense::where('expense_type', 'dokan_expense')->where('date', $date)->get();
            $carring = Expense::where('expense_type', 'carring_expense')->where('date', $date)->get();

            $pdf = PDF::loadView('admin.report.pdf.daily_summary', [
                'sales' => $sales,
                'purchase' => $purchase,
                'collection' => $collection,
                'payment' => $payment,
                'salary' => $salary,
                'bank' => $bank,
                'labour' => $labour,
                'dokan' => $dokan,
                'carring' => $carring,
                'date' => $date,

            ]);

            $date = now()->format('d-m-Y');
            return $pdf->stream('daily_summary_report(' . $date . ').pdf');
        } else {
        }
    }



    //monthly summary report
    public function monthlyReport()
    {
        return view('admin.summary.monthly');
    }

    //monthly summary pdf report
    public function monthlyReportDownload($date)
    {
        if ($date) {

            $month_name = date("F-Y", strtotime($date));

            $sales = CustomerLedger::where('type', 'sale')->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            //$sales = SalesCustomerInfo::where('date',$date)->get();
            $purchase = SupplierLedger::where('type', 'purchase')->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            $collection = CustomerLedger::where('payment', '>', 0)->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            $payment = SupplierLedger::where('payment', '>', 0)->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            $salary = Expense::where('expense_type', 'salary_expense')->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            $bank = Expense::where('expense_type', 'bank_expense')->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            $labour = Expense::where('expense_type', 'labour_expense')->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            $dokan = Expense::where('expense_type', 'dokan_expense')->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();

            $carring = Expense::where('expense_type', 'carring_expense')->whereMonth('date',  date('m', strtotime($date)))
                ->whereYear('date', date('Y', strtotime($date)))
                ->get();



            $pdf = PDF::loadView('admin.report.pdf.monthly_summary', [
                'sales' => $sales,
                'purchase' => $purchase,
                'collection' => $collection,
                'payment' => $payment,
                'salary' => $salary,
                'bank' => $bank,
                'labour' => $labour,
                'dokan' => $dokan,
                'carring' => $carring,
                'month_name' => $month_name,

            ]);

            $date = now()->format('d-m-Y');
            return $pdf->stream('monthly_summary_report(' . $date . ').pdf');
        }
    }



    //yearly summary report
    public function yearlyReport()
    {
        return view('admin.summary.yearly');
    }

    //yearly summary pdf report
    public function yearlyReportDownload($date)
    {

        if ($date) {

            $year =  date("Y", strtotime($date));


            $sales = CustomerLedger::where('type', 'sale')->whereYear('date', $date)->get();

            $purchase = SupplierLedger::where('type', 'purchase')->whereYear('date', $date)->get();

            $collection = CustomerLedger::where('payment', '>', 0)->whereYear('date', $year)->get();

            $payment = SupplierLedger::where('payment', '>', 0)->whereYear('date', $year)->get();

            $salary = Expense::where('expense_type', 'salary_expense')->whereYear('date', $date)->get();

            $bank = Expense::where('expense_type', 'bank_expense')->whereYear('date', $date)->get();

            $labour = Expense::where('expense_type', 'labour_expense')->whereYear('date', $date)->get();

            $dokan = Expense::where('expense_type', 'dokan_expense')->whereYear('date', $date)->get();

            $carring = Expense::where('expense_type', 'carring_expense')->whereYear('date', $date)->get();


            $pdf = PDF::loadView('admin.report.pdf.yearly_summary', [
                'sales' => $sales,
                'purchase' => $purchase,
                'collection' => $collection,
                'payment' => $payment,
                'salary' => $salary,
                'bank' => $bank,
                'labour' => $labour,
                'dokan' => $dokan,
                'carring' => $carring,
                'year' => $year,

            ]);

            $date = now()->format('d-m-Y');
            return $pdf->stream('yearly_summary_report(' . $date . ').pdf');
        } else {
        }
    }
}
