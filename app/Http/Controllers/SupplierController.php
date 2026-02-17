<?php

namespace App\Http\Controllers;

use App\Exports\SuppliersExport;
use App\Models\Supplier;
use App\Models\SupplierLedger;
use App\Models\SupplierTransactionDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    public function edit($id)
    {
        $supplier = Supplier::where('id',$id)->first();
        $data = SupplierLedger::where('supplier_id', $id)->get();
        if (count($data) != 0 && count($data) <= 1) {
            $count = 1;
            if ( $data[0]->type != 'other' ) {
                $count = 2;
            }
        } else {
            $count = count($data);
        }

        return view('admin.supplier.edit', compact('supplier', 'count'));
    }

    public static function recheck($id)
    {
        // logger(["logged", $id]);
        $alert = DB::transaction(function() use ($id) {
            $supplier = Supplier::where('id', $id)->first();
            if(!$supplier) {
                $alert = array('msg' => 'Supplier Not Found', 'alert-type' => 'warning');
                return $alert;
            }

            $transactions = SupplierLedger::where('supplier_id', $id)->orderBy('date', 'asc')->orderBy('id', 'asc')->get();
            if(count($transactions) == 0) {
                $alert = array('msg' => 'No Transaction Found', 'alert-type' => 'warning');
                return $alert;
            }
            $final_balance = 0;
            $counter = 0;
            foreach ($transactions as $row) {
                if($row->type == 'other' && $counter == 0){
                    $final_balance = $row->balance;
                }else{
                    $total_price = $row->type == 'return' ? -$row->total_price : $row->total_price;
                    $line_total = $total_price - $row->price_discount - $row->vat - $row->other_charge - $row->carring - $row->payment;
                    $final_balance += $line_total;
                    $row->balance = $final_balance;
                    $row->save();
                }
                $counter++;
            }

            $supplier->balance = $final_balance;
            $supplier->save();

            $alert = array('msg' => 'Supplier Successfully Updated', 'alert-type' => 'info');
            return $alert;
        });

        return redirect()->back()->with($alert);
    }

    public function update(Request $request)
    {
        $validator = $request->validate([

            'owner_name' => [ 'max:255'],
            'company_name' => [ 'max:255'],
            'officer_name' => [  'max:255'],
            'dealer_area' => [ 'max:255'],
            'dealer_code' => ['max:20'],
            'phone' => ['max:20'],
            'mobile' => [  'max:20',],
            'address' => [ 'max:255'],
            'ledger_page' => ['max:255'],
            'security' => ['max:255'],
            'credit_limit' => ['max:20'],
            'advance_payment' => ['max:20'],
            'previous_due' => ['max:20'],
            'condition' => ['max:200'],
            'starting_date' => ['max:20'],
            'photo' =>  ['image','mimes:jpeg,png,jpg,gif,svg','max:1000'],

        ]);
        $formated_starting_date = $validator['starting_date'] ? date('Y-m-d', strtotime($validator['starting_date'])) : null;

        if(!empty($request->photo)) {
            $photo = $request->photo;
            $photoName = uniqid().'.'.$photo->getClientOriginalExtension();
            $photo_path = $photo->move('images/supplier/',$photoName);
            if(file_exists($request->old_photo)){
                unlink($request->old_photo);
            }
        } else {
            if(!empty($request->old_photo)) {
                $photo_path=$request->old_photo;
            } else {
                $photo_path = "";
            }
        }

        $existing_supplier_data = SupplierLedger::where('supplier_id', $request->id)->orderBy('date', 'asc')->orderBy('id', 'asc')->get();
        if(count($existing_supplier_data) > 0){
            $ledger_id = $existing_supplier_data->first()->id;
            $new_balance = (double)$request->advance_payment ? -(double)$request->advance_payment : (double)$request->previous_due;
            if ($new_balance){
                SupplierLedger::where('id', $ledger_id)->update([
                    'balance'           => $new_balance,
                    'payment'           => $request->advance_payment ? $request->advance_payment : null,
                    'payment_remarks'   => $request->advance_payment ? "Advance Payment" : "Previous Due",
                    'date'              => $formated_starting_date,
                ]);
            }else{
                SupplierLedger::where('id', $ledger_id)->delete();
            }
        }else{
            $new_balance = (double)$request->advance_payment ? -(double)$request->advance_payment : (double)$request->previous_due;
            if (abs($new_balance) > 0) {
                SupplierLedger::insert([
                    'supplier_id'       => $request->id,
                    'type'              => 'other',
                    'balance'           => $new_balance ? $new_balance : 0,
                    'payment'           => $request->advance_payment ? $request->advance_payment : null,
                    'payment_remarks'   => $request->advance_payment ? "Advance Payment" : "Previous Due",
                    'date'              => $formated_starting_date
                ]);
            }
        }

        Supplier::where('id',$request->id)->update([
            'company_name' => $request->company_name,
            'owner_name' => $request->owner_name,
            'officer_name' => $request->officer_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'ledger_page' => $request->ledger_page,
            'condition' => $request->condition,
            'dealer_code' => $request->dealer_code,
            'dealer_area' => $request->dealer_area,
            'security' => $request->security,
            'credit_limit' => $request->credit_limit,
            'balance' => $new_balance,
            'starting_date' => $formated_starting_date,
            'photo' => $photo_path,

        ]);
        
        self::recheck($request->id);

        $alert = array('msg' => 'Supplier Successfully Updated', 'alert-type' => 'info');
        return redirect()->route('supplier.view', $request->id)->with($alert);

    }

    public function delete($id)
    {
        $getImg = Supplier::where('id',$id)->first();
        if(file_exists($getImg->photo ))
        {
            unlink($getImg->photo);
        }

        Supplier::where('id',$id)->delete();
        SupplierLedger::where('supplier_id', $id)->delete();
        SupplierTransactionDetails::where('supplier_id', $id)->delete();

        $alert = array('msg' => 'Supplier Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('supplier.index')->with($alert);
    }

    public function view($id)
    {
        $supplier = Supplier::where('id',$id)->first();
        return view('admin.supplier.view', compact('supplier'));
    }

    // public function status($id, $status)
    // {


    //     Supplier::where('id',$id)->update(['status' => $status == 1 ? 0 : 1]);

    //     $alert = array('msg' => 'Supplier Status Successfully Updated', 'alert-type' => 'info');
    //     return redirect()->route('supplier.index')->with($alert);

    // }

    // due show from here
    public function due()
    {
        return view('admin.supplier.due');

    }

    public function ledger_1($id)
    {

        $supplier = Supplier::where('id',$id)->first();
        if (!$supplier) {
            abort(404);  // This will show the 404 page
        }
        $supplier_ledger_info = SupplierLedger::where('supplier_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $products = SupplierTransactionDetails::where('supplier_id', $id)->get();
        return view('admin.supplier.ledger-1',get_defined_vars());

    }

    public function ledger_2($id)
    {

        $supplier = Supplier::where('id',$id)->first();
        if (!$supplier) {
            abort(404);  // This will show the 404 page
        }
        $supplier_ledger_info = SupplierLedger::where('supplier_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $products = SupplierTransactionDetails::where('supplier_id', $id)->get();
        return view('admin.supplier.ledger-2',get_defined_vars());

    }

    public function statement($id, Request $request)
    {

        $supplier = Supplier::where('id',$id)->first();
        if (!$supplier) {
            abort(404);  // This will show the 404 page
        }
        $version = $request->v;

        $supplier_ledger_info = SupplierLedger::where('supplier_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $products = SupplierTransactionDetails::where('supplier_id', $id)->get();
        if ($version == 1) {
            return view('admin.supplier.statement-1',get_defined_vars());
        }else {
            return view('admin.supplier.statement-2',get_defined_vars());
        }
    }

    public function purchase_list($id, Request $request)
    {
        $supplier = Supplier::where('id',$id)->first();
        if (!$supplier) {
            abort(404);  // This will show the 404 page
        }

        $version = $request->v;

        $start_date = $end_date = '';
        $supplier_ledgers = [];

        if ($request->start_date && $request->end_date) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $supplier_ledgers = SupplierLedger::where('supplier_id', $id)
                ->where(function ($query) {
                    $query->where('type', 'purchase')
                        ->orWhere('type', 'return');
                })
                ->whereBetween('date', [date('Y-m-d', strtotime($request->start_date)), date('Y-m-d', strtotime($request->end_date))])
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }else {
            $supplier_ledgers = SupplierLedger::where('supplier_id', $id)
                ->where(function ($query) {
                    $query->where('type', 'purchase')
                        ->orWhere('type', 'return');
                })
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $products = SupplierTransactionDetails::where('supplier_id', $id)->get();

        if ($version == 1) {
            return view('admin.supplier.purchase-list-1',get_defined_vars());
        }else {
            return view('admin.supplier.purchase-list-2',get_defined_vars());
        }
    }

    public function supplier_user(Request $request)
    {
        $searchTerm = $request->query('q');
        $suppliers = Supplier::where('company_name', 'LIKE', "%$searchTerm%")
            ->orWhere('address', 'LIKE', "%$searchTerm%")
            ->orWhere('mobile', 'LIKE', "%$searchTerm%")
            ->orWhere('id', $searchTerm)
            ->limit(20)
            ->get();

        if ($suppliers->isEmpty()) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $formattedSuppliers = $suppliers->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'text' => "{$supplier->company_name} - {$supplier->address} - {$supplier->mobile}",
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Request processed successfully',

            'data' => $formattedSuppliers,
        ]);
    }

    public function exportSupplier(Request $request, string $type = 'all', string $format = 'excel'){

        if ($request->has('q')) {
            $queryString = $request->q;
        } else {
            $queryString = "";
        }

        $query= Supplier::with(['ledgers' => function ($query) {
                $query->where('type', ['purchase', 'return', 'payment', 'other']);
            }])
            ->withSum(['ledgers as total_purchases' => function ($query) {
                $query->where('type', 'purchase');
            }], 'total_price')
            ->withSum(['ledgers as total_price_discounts' => function ($query) {
                $query->where('type', 'purchase');
            }], 'price_discount')
            ->withSum(['ledgers as total_vat' => function ($query) {
                $query->where('type', 'purchase');
            }], 'vat')
            ->withSum(['ledgers as total_carring' => function ($query) {
                $query->where('type', 'purchase')->orWhere('type', 'return');
            }], 'carring')
            ->withSum(['ledgers as total_others' => function ($query) {
                $query->where('type', 'purchase')->orWhere('type', 'return');
            }], 'other_charge')
            ->withSum(['ledgers as total_returns' => function ($query) {
                $query->where('type', 'return');
            }], 'total_price')
            ->withSum(['ledgers as total_payments' => function ($query) {
                $query->where('type', 'purchase')->orwhere('type', 'other')->orwhere('type', 'payment');
            }], 'payment')
            ->withSum(['ledgers as previous_due' => function ($query) {
                $query->where('type', 'other')->where('balance', '>', 0);
            }], 'balance')
            ->where(function($query) use ($queryString) {
                $query->where('company_name', 'LIKE', "%$queryString%")
                    ->orWhere('address', 'LIKE', "%$queryString%")
                    ->orWhere('mobile', 'LIKE', "%$queryString%")
                    ->orWhere('id', $queryString);
            })
            ->orderBy('id', 'asc');

        if ($type == 'due') {
            $suppliers = $query->where('balance', '>', 0)->get();
        } else {
            $suppliers = $query->get();
        }

        $supplierIds = $suppliers->pluck('id')->toArray();
        $tranx_all = SupplierTransactionDetails::whereIn('supplier_id', $supplierIds)->get();
        $group_by_supplier = $tranx_all->groupBy('supplier_id')->map(function ($items) {
            // Initialize totals array for products
            $totals = [];

            // Iterate through each item and sum up the sale and return quantities
            $items->each(function ($item) use (&$totals) {
                $transactionType = $item->transaction_type; // e.g., 'sale' or 'return'
                $productType = $item->product->type;         // e.g., 'bag' or 'kg'
                $quantity = $item->quantity;
                $discount_quantity = $item->discount_qty;

                // Ensure that the product type is initialized in the totals array
                if (!isset($totals[$productType])) {
                    $totals[$productType] = [
                        'return' => 0,
                        'discount' => 0,
                        'purchase' => 0,
                    ];
                }

                // Accumulate the quantities for each transaction type
                if ( $transactionType == 'purchase' ) {
                    $totals[$productType]['purchase'] += $quantity;
                    if ($discount_quantity > 0){
                        $totals[$productType]['discount'] += $discount_quantity;
                    }
                } elseif ( $transactionType == 'return' ) {
                    $totals[$productType]['return'] += $quantity;
                }
            });

            return $totals;
        });

        $supplierWeights = SupplierTransactionDetails::select('supplier_id',
            DB::raw("SUM(CASE WHEN transaction_type = 'purchase' THEN weight * (quantity - discount_qty) ELSE 0 END) as total_purchased_weight"),
            DB::raw("SUM(CASE WHEN transaction_type = 'return' THEN weight * (quantity - discount_qty) ELSE 0 END) as total_returned_weight"),
            DB::raw("SUM(CASE WHEN transaction_type = 'purchase' THEN weight * (quantity - discount_qty) ELSE 0 END) - SUM(CASE WHEN transaction_type = 'return' THEN weight * quantity ELSE 0 END) as net_weight")
        )
        ->groupBy('supplier_id')
        ->get();

        $file_name = 'cuutomer-list.pdf';
        $columns = [
            'id' => 'SL',
            'name' => 'Company Name',
            'address' => 'Address',
            'phone' => 'Mobile',
            'ledger' => 'Ledger',
            'credit_limit' => 'Credit Limit',
            'quantity' => 'Quantity',
            'discount_qty' => 'Dis. Qty',
            'return_qty' => 'Return Qty',
            'purchase_qty' => 'Pur Qty',
            'weight' => 'Weight',
            'purchase_amount' => 'Pur. (Tk)',
            'return' => 'Return',
            'discount' => 'Dis. (Tk)',
            'total' => 'Total (Tk)',
            'vat' => 'VAT',
            'carring' => 'Carring',
            'others' => 'Others',
            'payment' => 'Payment',
            'total_payment' => 'Total Payment',
            'old_due' => 'Old Due',
            'balance' => 'Balance (Tk)',
        ];
        if ( $type == 'due' ) {
            $file_name = 'supplier-due-list.pdf';
            $columns = [
                'id' => 'SL',
                'name' => 'Company Name',
                'address' => 'Address',
                'phone' => 'Mobile',
                'ledger' => 'Ledger',
                'purchase_qty' => 'Pur. Qty',
                'weight' => 'Weight',
                'total' => 'Total (Tk)',
                'payment' => 'Payment',
                'balance' => 'Balance (Tk)',
            ];
        }

        if($format == 'excel'){
            $file_name = 'supplier-list.xlsx';
            if($type == 'due'){
                $file_name = 'supplier-due-list.xlsx';
            }
            return Excel::download(new SuppliersExport($suppliers, $group_by_supplier, $supplierWeights, $columns, $type), $file_name, \Maatwebsite\Excel\Excel::XLSX);
        } else if($format == 'csv'){
            $file_name = 'supplier-list.csv';
            if($type == 'due'){
                $file_name = 'supplier-due-list.csv';
            }
            return Excel::download(new SuppliersExport($suppliers, $group_by_supplier, $supplierWeights, $columns, $type), $file_name, \Maatwebsite\Excel\Excel::CSV);
        } else if($format == 'pdf'){
            $pdf = Pdf::loadView('admin.supplier.pdf.all', ['suppliers' => $suppliers, 'group_by_supplier' => $group_by_supplier, 'supplierWeights' => $supplierWeights, 'columns' => $columns, 'type' => $type]);
            $pdf->setOption(['dpi' => 100, 'defaultFont' => 'sans-serif']);
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            return $pdf->stream($file_name);
        }
    }

}
