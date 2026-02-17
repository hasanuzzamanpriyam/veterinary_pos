<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;

class HomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        /* ================= SALES ================= */

        // Total Sales Amount
        $totalSalesToday = CustomerLedger::where('type', 'sale')
            ->whereDate('date', $today)
            ->sum('total_price');

        // Total Sales Quantity
        $total_qty_sales_today = CustomerLedger::where('type', 'sale')
            ->whereDate('date', $today)
            ->sum('total_qty');

        // Total Sales Payment
        $totalPaymentToday = CustomerLedger::where('type', 'sale')
            ->whereDate('date', $today)
            ->sum('payment');

        // Total Invoice Count
        $totalInvoiceCount = CustomerLedger::where('type', 'sale')
            ->whereDate('date', $today)
            ->count();

        // Total Sales Weight
        $todaysTotalSellsWeight = CustomerTransactionDetails::where('transaction_type', 'sale')
            ->whereDate('date', $today)
            ->sum(DB::raw('CAST(weight AS DECIMAL(10,2))'));

        // Total Sales Due
        $totalSalesToday = $totalSalesToday ?? 0;
        $totalPaymentToday = $totalPaymentToday ?? 0;
        $totalDueToday = $totalSalesToday - $totalPaymentToday;

        /* ================= COLLECTION ================= */

        $totalCollectionToday = CustomerLedger::where('type', 'collection')
            ->whereDate('date', $today)
            ->sum('payment');

        /* ================= PURCHASE ================= */

        $totalPurchaseToday = SupplierLedger::where('type', 'purchase')
            ->whereDate('date', $today)
            ->sum('total_price');

        $total_qty_purchase_today = SupplierLedger::where('type', 'purchase')
            ->whereDate('date', $today)
            ->sum('total_qty');

        // Total Purchase Weight
        $todaysTotalPurchaseWeight = SupplierTransactionDetails::where('transaction_type', 'purchase')
            ->whereDate('date', $today)
            ->sum(DB::raw('CAST(weight AS DECIMAL(10,2))'));

        // Total Purchase Due
        $totalPurchaseToday = $totalPurchaseToday ?? 0;
        $totalCollectionToday = $totalCollectionToday ?? 0;
        $totalPurchaseDueToday = $totalPurchaseToday - $totalCollectionToday;

        return view('admin.home', compact(
            'totalSalesToday',
            'total_qty_sales_today',
            'todaysTotalSellsWeight',
            'totalPaymentToday',
            'totalInvoiceCount',
            'totalCollectionToday',
            'totalPurchaseToday',
            'total_qty_purchase_today'
        ));
    }
}
