<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\ProductStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index_1()
    {

        $customer_ledger = CustomerLedger::where('type', 'sale')
            ->orderBy('date', 'DESC')
            ->get();
        $products = CustomerTransactionDetails::where('transaction_type', 'sale')->get();
        $customers = customer::get();

        return view('admin.sales.index1', get_defined_vars());
    }
    public function index_2()
    {

        $customer_ledger = CustomerLedger::where('type', 'sale')
            ->orderBy('date', 'DESC')
            ->get();
        $products = CustomerTransactionDetails::where('transaction_type', 'sale')->get();

        return view('admin.sales.index2', get_defined_vars());
    }

    public function view($invoice, Request $request)
    {
        $view = $request->query('view') ?? 'sale';
        $customer_info = CustomerLedger::where('id', $invoice)->first();
        $products = CustomerTransactionDetails::where('transaction_id', $invoice)->get();
        if ($view == 'return') {
            return view('admin.sales.return_view', get_defined_vars());
        } else if ($view == 'collection') {
            return view('admin.account.collection.view', get_defined_vars());
        } else {
            return view('admin.sales.view', get_defined_vars());
        }
    }

    private function calculate_last_row($row)
    {

        $amount_to_deduct = 0;
        // logger($row);

        if ($row) {
            $inv_type = $row->type;
            $amount_to_deduct = 0;
            if ($inv_type == 'sale') {
                $amount_to_deduct = $row->total_price + $row->vat + $row->carring + $row->other_charge - $row->price_discount - $row->payment;
            } elseif ($inv_type == 'collection') {
                $amount_to_deduct = -$row->payment;
            } elseif ($inv_type == 'return') {
                $amount_to_deduct = - ($row->total_price - $row->carring - $row->other_charge);
            } elseif ($inv_type == 'other') {
                $amount_to_deduct = $row->balance; // others
            }
        }
        // logger($amount_to_deduct);

        return $amount_to_deduct;
    }

    private function update_stock($invoice, $type)
    {
        // Delete transaction and update stock
        // sale: increment stock
        // return: decrement stock
        if (in_array(strtolower($type), ['return', 'sale'])) {
            $tnxs = CustomerTransactionDetails::where('transaction_id', $invoice)->get();
            $tnxs->each(function ($tnx) {
                $product_stock = ProductStore::where([
                    'product_id' => $tnx->product_id,
                    'product_store_id' => $tnx->product_store_id,
                ])->first();

                // update stock based on `transaction_type`
                if ($product_stock) {
                    if ($tnx->transaction_type == 'return') {
                        $product_stock->decrement('product_quantity', $tnx->quantity);
                    } else {
                        $product_stock->increment('product_quantity', $tnx->quantity);
                    }
                }

                // finally delete the transaction
                $tnx->delete();
            });
        }
    }

    public function delete($invoice, Request $request)
    {
        $view = $request->query('view') ?? 'sale';

        // invoice to delete
        $invToDelete = CustomerLedger::find($invoice);
        if ($invToDelete) {

            $this->update_stock($invoice, $invToDelete->type);

            $invToDelete->delete();
            CustomerController::recheck($invToDelete->customer_id);
        }
        CustomerTransactionDetails::where('transaction_id', $invoice)->delete();

        // @todo [increase stock] from [ProductStore]

        $alert = array('msg' => ucfirst($view) . ' invoice deleted successfully', 'alert-type' => 'warning');
        return redirect()->back()->with($alert);
    }

    public function returnIndex()
    {
        $sale_returns = CustomerLedger::where('type', 'return')
            ->orderBy('date', 'DESC')
            ->get();
        $products = CustomerTransactionDetails::where('transaction_type', 'return')->get();
        return view('admin.sales.return_index', get_defined_vars());
    }

    public function returnView($invoice)
    {
        $customer_info = CustomerLedger::where('id', $invoice)->first();
        $products = CustomerTransactionDetails::where('transaction_id', $invoice)->get();
        return view('admin.sales.return_view', get_defined_vars());
    }

    public function searchCustomer(Request $request)
    {
        // dd($request);

        $customers = customer::get();
        // $customer_ledger = CustomerLedger::where('type', 'sale')->whereBetween('date', [$request->start_date, $request->end_date])->get();
        $customer_ledger = CustomerLedger::query()
            ->where('type', 'sale')
            ->when(isset($request->start_date), function (Builder $query) use ($request) {
                $query->whereDate('date', '>=', date('Y-m-d', strtotime($request->start_date)));
            })
            ->when(isset($request->end_date), function (Builder $query) use ($request) {
                $query->whereDate('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            })
            ->when(isset($request->get_customer_id), function (Builder $query) use ($request) {
                $query->where('customer_id', $request->get_customer_id);
            })
            ->get();
        return view('admin.sales.index1', get_defined_vars());
    }

    public function SaleView($invoice)
    {
        $customer_info = CustomerLedger::where('id', $invoice)->first();
        $products = CustomerTransactionDetails::where('transaction_id', $invoice)->get();

        return view('admin.sales.pdf.pdf', compact('products', 'customer_info'));
    }


    // return sales print method

    public function returnSalesPrint($invoice)
    {
        $customer_info = CustomerLedger::where('id', $invoice)->first();
        $products = CustomerTransactionDetails::where('transaction_id', $invoice)->get();
        return view('admin.sales.return_pdf.pdf', compact('products', 'customer_info'));
    }

    // sales invoice search

    public function salesInvoiceSearch()
    {

        return view('admin.sales.invoice_search');
    }

    public function salesInvoiceSearched(Request $request)
    {

        $invoiceNumber = $request->input('sales_invoices_no');

        $customer_info = CustomerLedger::where('id', $invoiceNumber)->first();

        $products = CustomerTransactionDetails::where('transaction_id', $invoiceNumber)->get();

        return view('admin.sales.invoice_search', compact('products', 'customer_info', 'invoiceNumber'));
    }

    public function route_to_index_1()
    {
        return redirect()->route('sales.index');
    }
}
