<?php

namespace App\Http\Controllers;

use App\Models\SupplierLedger;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //show payment list table from here
    public function index()
    {
        $payment_list = SupplierLedger::where('payment', '>', 0)->orderBy('date', 'DESC')->get();
        return view('admin.account.payment.index', compact('payment_list'));
    }
    
    public function print($id)
    {
        $invoice = SupplierLedger::where('id', $id)->first();
        return view('admin.account.payment.print', compact('invoice'));
    }

    //get collection report from here
    public function report()
    {
        return view('admin.account.payment.report');
    }

    public function paymentMemoSearch()
    {
        return view('admin.account.payment.memo-search');
    }

    public function paymentMemoSearched(Request $request)
    {
        $memoNumber = $request->input('payment_memo_no');

        $paymentMemo = SupplierLedger::where('type', 'payment')
            ->where('id', $memoNumber)->first();
        return view('admin.account.payment.memo-search', get_defined_vars());
    }
}
