<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\SupplierTransactionDetails;
use App\Models\SupplierLedger;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AllReportController extends Controller
{
    public function sales()
    {
        return view('admin.report.sales');
    }

    public function customer_wise_sales_1(Request $request, $id)
    {
        // dd($request, $id);
        $customer = customer::where('id', $id)->first();
        $customer_ledger = CustomerLedger::query()
            ->where('customer_id', $id)
            ->where('type', 'sale')
            ->orWhere('type', 'return')
            ->when(isset($request->start_date), function (Builder $query) use ($request) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($request->start_date)));
            })
            ->when(isset($request->end_date), function (Builder $query) use ($request) {
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            })
            ->when(isset($id), function (Builder $query) use ($id) {
                $query->where('customer_id', $id);
            })
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();
        // dd($customer_ledger);
        $transactionDetails = CustomerTransactionDetails::where('transaction_type', 'sale')
            ->orWhere('transaction_type', 'return')
            ->where('customer_id', $id)
            ->get();
        $products = CustomerTransactionDetails::where('customer_id', $id)->get();
        return view('admin.sales.customer.report1', get_defined_vars());
    }
    public function customer_wise_sales_2(Request $request, $id)
    {
        // dd($request, $id);
        $customer = customer::where('id', $id)->first();
        $customer_ledger = CustomerLedger::query()
            ->whereIn('type', ['sale', 'return'])
            ->when(isset($request->start_date), function (Builder $query) use ($request) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($request->start_date)));
            })
            ->when(isset($request->end_date), function (Builder $query) use ($request) {
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            })
            ->when(isset($id), function (Builder $query) use ($id) {
                $query->where('customer_id', $id);
            })
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();
        $transactionDetails = CustomerTransactionDetails::where('transaction_type', 'sale')
            ->orWhere('transaction_type', 'return')
            ->where('customer_id', $id)
            ->get();
        return view('admin.sales.customer.report2', get_defined_vars());
    }
    public function purchase()
    {
        return view('admin.report.purchase');
    }

    //purchase report pdf generator method for specific customer
    public function salesCustomerReportDownload($start_date, $end_date, $id)
    {
        set_time_limit(300);

        if ($id && ($start_date && $end_date)) {
            $customer_info = customer::where('id', $id)->first();
            $reports = CustomerLedger::where('customer_id', $id)->where('type', 'sale')->whereBetween('date', [$start_date, $end_date])->with('store')->orderBy('id', 'DESC')->get();
            $products = CustomerTransactionDetails::where('customer_id', $id)->where('transaction_type', 'sale')->whereBetween('date', [$start_date, $end_date])->with('product')->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView('admin.report.pdf.sales', ['start_date' => $start_date, 'end_date' => $end_date, 'reports' => $reports, 'products' => $products, 'customer_info' => $customer_info]);
            $pdf->setOption('enable_font_subsetting', true);
            $pdf->setOption('isFontSubsettingEnabled', true);
            $date = now()->format('d-m-Y');
            return $pdf->stream('invoice(' . $date . ').pdf');
        } else {
        }
    }


    //purchase report pdf generator method for all customer
    public function salesAllReportDownload($start_date, $end_date)
    {
        set_time_limit(300);
        //dd($start_date, $end_date);

        if ($start_date && $end_date) {
            $all_reports = CustomerLedger::where('type', 'sale')->whereBetween('date', [$start_date, $end_date])->with('store')->orderBy('id', 'DESC')->get();
            $products = CustomerTransactionDetails::where('transaction_type', 'sale')->whereBetween('date', [$start_date, $end_date])->with('product')->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView('admin.report.pdf.sales', ['start_date' => $start_date, 'end_date' => $end_date, 'all_reports' => $all_reports, 'products' => $products]);
            $pdf->setOption('enable_font_subsetting', true);
            $pdf->setOption('isFontSubsettingEnabled', true);
            $date = now()->format('d-m-Y');
            return $pdf->stream('invoice(' . $date . ').pdf');
        } else {
        }
    }

    //purchase report pdf generator method for specific supplier
    public function purchaseSupplierReportDownload($start_date, $end_date, $id)
    {

        if ($id && ($start_date && $end_date)) {
            $supplier_info = Supplier::where('id', $id)->first();
            $reports = SupplierLedger::where('supplier_id', $id)->whereIn('type', ['purchase', 'return'])->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $products = SupplierTransactionDetails::where('supplier_id', $id)->whereIn('transaction_type', ['purchase', 'return'])->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView('admin.report.pdf.purchase', ['start_date' => $start_date, 'end_date' => $end_date, 'reports' => $reports, 'products' => $products, 'supplier_info' => $supplier_info]);
            $date = now()->format('d-m-Y');
            return $pdf->stream('invoice(' . $date . ').pdf');
        } else {
        }
    }

    //purchase report pdf generator method for all supplier
    public function purchaseAllReportDownload($start_date, $end_date)
    {

        if ($start_date && $end_date) {
            $all_reports = SupplierLedger::whereIn('type', ['purchase', 'return'])->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $products = SupplierTransactionDetails::whereIn('transaction_type', ['purchase', 'return'])->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView('admin.report.pdf.purchase', ['start_date' => $start_date, 'end_date' => $end_date, 'all_reports' => $all_reports, 'products' => $products]);
            $date = now()->format('d-m-Y');
            return $pdf->stream('invoice(' . $date . ').pdf');
        } else {
        }
    }

    //customer collection pdf report generator method for specific customer
    public function collectionCustomerReportDownload($start_date, $end_date, $id)
    {

        if ($id && ($start_date && $end_date)) {
            $reports = CustomerLedger::where('customer_id', $id)->where('type', 'collection')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView('admin.report.pdf.collection', ['start_date' => $start_date, 'end_date' => $end_date, 'reports' => $reports]);
            $date = now()->format('d-m-Y');
            return $pdf->stream('collection_report(' . $date . ').pdf');
        } else {
        }
    }

    //customer collection pdf report generator method for all customer
    public function collectionAllReportDownload($start_date, $end_date)
    {

        if ($start_date && $end_date) {
            $all_reports = CustomerLedger::where('type', 'collection')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $pdf = PDF::loadView('admin.report.pdf.collection', ['start_date' => $start_date, 'end_date' => $end_date, 'all_reports' => $all_reports]);
            $date = now()->format('d-m-Y');
            return $pdf->stream('collection_report(' . $date . ').pdf');
        } else {
        }
    }

    //supplier payment pdf report generator method
    public function paymentSupplierReportDownload($start_date, $end_date, $id)
    {

        if ($id && ($start_date && $end_date)) {
            $reports = SupplierLedger::where('supplier_id', $id)->where('type', 'payment')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

            $pdf = PDF::loadView('admin.report.pdf.payment', ['start_date' => $start_date, 'end_date' => $end_date, 'reports' => $reports]);
            $date = now()->format('d-m-Y');
            return $pdf->stream('payment_report(' . $date . ').pdf');
        } else {
        }
    }

    //supplier payment pdf report generator method
    public function paymentAllReportDownload($start_date, $end_date)
    {

        if ($start_date && $end_date) {
            $all_reports = SupplierLedger::where('type', 'payment')->whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            $pdf = PDF::loadView('admin.report.pdf.payment', ['start_date' => $start_date, 'end_date' => $end_date, 'all_reports' => $all_reports]);
            $date = now()->format('d-m-Y');
            return $pdf->stream('payment_report(' . $date . ').pdf');
        } else {
        }
    }


    public function purchaseReport()
    {
        $purchases = Supplier::with(['transactions'])
            ->get()
            ->map(function ($supplier) {
                return (object)[
                    'supplier_id' => $supplier->id,
                    'company_name' => $supplier->company_name,
                    'address' => $supplier->address,
                    'mobile' => $supplier->mobile,
                    'total_quantity' => $supplier->transactions->sum('quantity'),
                    'total_amount' => $supplier->transactions->sum('total_price'),
                ];
            })
            ->sortByDesc('total_amount'); // descending order

        $totalSuppliers = $purchases->count();
        $grandTotal = $purchases->sum('total_amount');

        return view('admin.report.purchase', compact('purchases', 'totalSuppliers', 'grandTotal'));
    }
}
