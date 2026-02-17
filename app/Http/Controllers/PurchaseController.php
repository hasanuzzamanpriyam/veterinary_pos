<?php

namespace App\Http\Controllers;

use App\Models\ProductStore;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{

    //purchase view
    public function view($invoice, Request $request)
    {
        $view = $request->query('view') ?? 'purchase';
        // dd($request, $view);
        $supplier_info = SupplierLedger::where('id', $invoice)->first();
        // dd($supplier_info);
        $type = false;
        if ($supplier_info){
            $type = $supplier_info->type;
        }

        $products = SupplierTransactionDetails::where('transaction_id', $invoice)->get();
        if ($view == 'return' && $view == $type) {
            return view('admin.purchase.return_view', get_defined_vars());
        } else if ($view == 'payment' && $view == $type) {
            return view('admin.account.payment.view', get_defined_vars());
        } else if ( $view == $type && ($view == 'purchase' || $view == 'other') ) {
            return view('admin.purchase.view', get_defined_vars());
        }else{
            abort(404);
        }
    }

    public function print($invoice)
    {
        $supplier_info = SupplierLedger::where('id', $invoice)->first();
        $products = SupplierTransactionDetails::where('transaction_id', $invoice)->get();
        return view('admin.purchase.print2', get_defined_vars());
    }

    private function calculate_last_row($row)
    {

        $amount_to_deduct = 0;

        if ($row) {
            $inv_type = $row->type;
            $amount_to_deduct = 0;
            if( $inv_type == 'purchase' ){
                $amount_to_deduct = $row->total_price - $row->vat - $row->carring - $row->other_charge - $row->price_discount - $row->payment;
            } elseif( $inv_type == 'payment' ){
                $amount_to_deduct = -$row->payment; // need to increament that's why prepend (-)
            } elseif ( $inv_type == 'return' ){
                $amount_to_deduct = -($row->total_price - $row->carring - $row->other_charge);
            } elseif ( $inv_type == 'other' ){
                $amount_to_deduct = $row->balance; // others
            }
        }
        return $amount_to_deduct;
    }

    private function update_stock( $invoice, $type )
    {
        // Delete transaction and update stock
        // purcahse: decrement stock
        // return: increment stock
        if (in_array(strtolower($type), ['return', 'purchase'])) {
            $tnxs = SupplierTransactionDetails::where('transaction_id', $invoice)->get();
            $tnxs->each(function ($tnx) {
                $product_stock = ProductStore::where([
                    'product_id' => $tnx->product_id,
                    'product_store_id' => $tnx->product_store_id,
                ])->first();

                // update stock based on `transaction_type`
                if ($product_stock) {
                    if ( $tnx->transaction_type == 'return' ) {
                        $product_stock->increment('product_quantity', $tnx->quantity);
                    }else{
                        $product_stock->decrement('product_quantity', $tnx->quantity);
                    }
                }

                // finally delete the transaction
                $tnx->delete();
            });
        }
    }
    // delete purchase
    public function delete($invoice, Request $request)
    {
        $view = $request->query('view') ?? 'purchase';

        // invoice to delete
        $invToDelete = SupplierLedger::find($invoice);

        if( $invToDelete ){

            $this->update_stock( $invoice, $invToDelete->type );

            $invToDelete->delete();
            SupplierController::recheck($invToDelete->supplier_id);
        }

        SupplierTransactionDetails::where('transaction_id', $invoice)->delete();

        $alert = array(
            'msg' => ucfirst($view) .' Order Successfully Deleted',
            'alert-type' => 'success');
        return redirect()->back()->with($alert);
    }

    public function searchSupplier(Request $request)
    {
        $supplier_ledger = SupplierLedger::where('type', 'purchase')->whereBetween('date', [$request->start_date, $request->end_date])->get();
        return view('admin.purchase.index1', get_defined_vars());
    }

    public function purchaseReturnPrint($invoice)
    {
        $supplier_ledger = SupplierLedger::where('id', $invoice)->first();
        $products = SupplierTransactionDetails::where('transaction_id', $invoice)->get();
        return view('admin.purchase.return_print', get_defined_vars());
    }

    public function route_to_index_1()
    {
        return redirect()->route('purchase.index');
    }
}
