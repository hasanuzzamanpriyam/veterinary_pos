<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\customer;
use App\Models\PriceGroup;
use App\Models\CustomerLedger;
use App\Models\CustomerTransactionDetails;
use App\Models\CustomerTypes;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public $customer_search;

    public $customer_types = ['Fish', 'Poultry Sonali', 'Poultry Layer', 'Poultry Boiler', 'Cement', 'All'];

    public function search_user($id)
    {
        $item = customer::find($id);
        return response()->json($item);
    }

    public static function recheck($id)
    {
        $alert = DB::transaction(function() use ($id) {
            $customer = customer::where('id', $id)->first();

            if(!$customer) {
                $alert = array('msg' => 'Customer Not Found', 'alert-type' => 'warning');
                return $alert;
            }

            $transactions = CustomerLedger::where('customer_id', $id)->orderBy('date', 'asc')->orderBy('id', 'asc')->get();
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
                    $line_total = $total_price - $row->price_discount + $row->vat + $row->other_charge + $row->carring - $row->payment;
                    $final_balance += $line_total;
                    $row->balance = $final_balance;
                    $row->save();
                }
                $counter++;
            }

            $customer->balance = $final_balance;
            $customer->save();

            $alert = array('msg' => 'Customer Successfully Updated', 'alert-type' => 'info');
            return $alert;
        });

        return redirect()->back()->with($alert);
    }

    public function customer_user(Request $request)
    {
        $searchTerm = $request->query('q');
        $customers = customer::where('name', 'LIKE', "%$searchTerm%")
            ->orWhere('address', 'LIKE', "%$searchTerm%")
            ->orWhere('mobile', 'LIKE', "%$searchTerm%")
            ->orWhere('id', $searchTerm)
            ->limit(20)
            ->get();

        if ($customers->isEmpty()) {
            return response()->json(['message' => 'Customers not found'], 404);
        }

        $formattedCustomers = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => "{$customer->name} - {$customer->address} - {$customer->mobile}",
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Request processed successfully',

            'data' => $formattedCustomers,
        ]);
    }


    public function edit($id)
    {

        $customer = customer::where('id', $id)->first();
        if (!$customer) {
            abort(404);  // This will show the 404 page
        }
        $price_groups = PriceGroup::get();
        $customer_types = CustomerTypes::get();
        $data = CustomerLedger::where('customer_id', $id)->get();
        if (count($data) > 0 && count($data) <= 1) {
            $count = 1;
            if ( $data[0]->type != 'other' ) {
                $count = 2;
            }
        } else {
            $count = count($data);
        }

        // need to check if customer ledger type is equal to other
        // then
        return view('admin.customer.edit', get_defined_vars());
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['max:255'],
            'father_name' => ['max:255'],
            'company_name' => ['max:255'],
            'phone' => ['max:14'],
            'mobile' => ['max:14'],
            'address' => ['max:255'],
            'nid' => ['max:20'],
            'birthday' => ['max:10'],
            'ledger_page' => ['max:100'],
            'type' => ['max:255'],
            'price_group_id' => ['max:11'],
            'security' => ['max:255'],
            'credit_limit' => ['max:20'],
            'advance_payment' => ['max:20'],
            'previous_due' => ['max:20'],
            'starting_date' => ['max:10'],
            'guarantor_name' => ['max:255'],
            'guarantor_company_name' => ['max:255'],
            // 'guarantor_birthday' => ['max:10'],
            'guarantor_birthday' => 'nullable|date',
            'guarantor_mobile' => ['max:14'],
            'guarantor_father_name' => ['max:255'],
            'guarantor_phone' => ['max:14'],
            'guarantor_address' => ['max:255'],
            'guarantor_security' => ['max:255'],
            'guarantor_nid' => ['max:255'],
            'guarantor_remarks' => ['max:255'],
            'photo' =>  ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1000'],
            'guarantor_photo' =>  ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1000'],
        ]);


        if (!empty($request->photo)) {
            $photo = $request->photo;
            $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo_path = $photo->move('images/customer/', $photoName);
            if (file_exists($request->old_photo)) {
                unlink($request->old_photo);
            }
        } else {
            if (!empty($request->old_photo)) {
                $photo_path = $request->old_photo;
            } else {
                $photo_path = "";
            }
        }

        if (!empty($request->guarantor_photo)) {
            $guarantor_photo = $request->guarantor_photo;
            $guarantor_photoName = uniqid() . '.' . $guarantor_photo->getClientOriginalExtension();
            $guarantor_photo_path = $guarantor_photo->move('images/customer/', $guarantor_photoName);
            if (file_exists($request->guarantor_old_photo)) {
                unlink($request->guarantor_old_photo);
            }
        } else {
            if (!empty($request->guarantor_old_photo)) {
                $guarantor_photo_path = $request->guarantor_old_photo;
            } else {
                $guarantor_photo_path = "";
            }
        }


        $existing_customer_data = CustomerLedger::where('customer_id', $request->id)->orderBy('date', 'asc')->orderBy('id', 'asc')->get();
        if(count($existing_customer_data) > 0){
            $ledger_id = $existing_customer_data->first()->id;
            $new_balance = (double)$request->advance_payment ? -(double)$request->advance_payment : (double)$request->previous_due;
            if ($new_balance){
                CustomerLedger::where('id', $ledger_id)->update([
                    'balance' => $new_balance,
                    'payment' => $request->advance_payment ?? 0,
                    'date'        => date('Y-m-d', strtotime($request->starting_date)),
                    'remarks'     => $request->advance_payment ? "Advance Collection" : "Previous Due",
                    'received_by' => $request->advance_payment ? "Advance Collection" : "Previous Due",
                ]);
            }else{
                CustomerLedger::where('id', $ledger_id)->delete();
            }
        }else{
            // Create new ledger item
            $new_balance = (double)$request->advance_payment ? -(double)$request->advance_payment : (double)$request->previous_due;
            if (abs($new_balance) > 0) {
                CustomerLedger::insert([
                    'customer_id' => $request->id,
                    'type'        => 'other',
                    'balance'     => $new_balance ? $new_balance : 0,
                    'payment'     => $request->advance_payment ?? 0,
                    'remarks'     => $request->advance_payment ? "Advance Collection" : "Previous Due",
                    'received_by' => $request->advance_payment ? "Advance Collection" : "Previous Due",
                    'date'        => date('Y-m-d', strtotime($request->starting_date)),
                ]);
            }
        }


        customer::where('id', $request->id)->update([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'nid' => $request->nid,
            'birthday' => date('Y-m-d', strtotime($request->birthday)),
            'ledger_page' => $request->ledger_page,
            'type' => $request->type,
            'price_group_id' => $request->price_group,
            'security' => $request->security,
            'credit_limit' => $request->credit_limit,
            'balance' => $new_balance,
            'starting_date' => date('Y-m-d', strtotime($request->starting_date)),
            'photo' => $photo_path,
            'guarantor_name' => $request->guarantor_name,
            'guarantor_company_name' => $request->guarantor_company_name,
            'guarantor_birthday' => date('Y-m-d', strtotime($request->guarantor_birthday)),
            'guarantor_mobile' => $request->guarantor_mobile,
            'guarantor_father_name' => $request->guarantor_father_name,
            'guarantor_phone' => $request->guarantor_phone,
            'guarantor_email' => $request->guarantor_email,
            'guarantor_address' => $request->guarantor_address,
            'guarantor_security' => $request->guarantor_security,
            'guarantor_nid' => $request->guarantor_nid,
            'guarantor_remarks' => $request->guarantor_remarks,
            'guarantor_photo' => $guarantor_photo_path,
        ]);

        self::recheck($request->id);

        $alert = array('msg' => 'Customer Successfully Updated', 'alert-type' => 'info');
        return redirect()->route('customer.index')->with($alert);
    }

    public function delete($id)
    {
        $getImg = customer::where('id', $id)->first();
        if (!$getImg) {
            abort(404);  // This will show the 404 page
        }
        if (file_exists($getImg->photo)) {
            unlink($getImg->photo);
            if (file_exists($getImg->guarantor_photo)) {
                unlink($getImg->guarantor_photo);
            }
        }

        customer::where('id', $id)->delete();
        CustomerLedger::where('customer_id', $id)->delete();
        CustomerTransactionDetails::where('customer_id', $id)->delete();

        $alert = array('msg' => 'Customer Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('customer.index')->with($alert);
    }

    public function view($id)
    {
        $customer = customer::where('id', $id)->first();
        if (!$customer) {
            abort(404);  // This will show the 404 page
        }
        $customer_types = CustomerTypes::latest()->get();
        return view('admin.customer.view', compact('customer', 'customer_types'));
    }

    public function gallary()
    {

        $customers = customer::latest()->get();
        return view('admin.customer.gallary', get_defined_vars());
    }

    public function due()
    {

        return view('admin.customer.due');
    }

    public function ledger_1($id)
    {

        $customer = customer::where('id', $id)->first();
        if (!$customer) {
            abort(404);  // This will show the 404 page
        }

        $customer_ledger_info = CustomerLedger::where('customer_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $products = CustomerTransactionDetails::where('customer_id', $id)->get();
        return view('admin.customer.ledger-1', get_defined_vars());
    }

    public function ledger_2($id)
    {

        $customer = customer::where('id', $id)->first();
        if (!$customer) {
            abort(404);  // This will show the 404 page
        }

        $customer_ledger_info = CustomerLedger::where('customer_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $products = CustomerTransactionDetails::where('customer_id', $id)->get();
        return view('admin.customer.ledger-2', get_defined_vars());
    }

    public function statement1($id)
    {

        $customer = customer::where('id', $id)->first();
        if (!$customer) {
            abort(404);  // This will show the 404 page
        }
        $customer_ledger_info = CustomerLedger::where('customer_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $products = CustomerTransactionDetails::where('customer_id', $id)->get();
        return view('admin.customer.statement1', get_defined_vars());
    }
    public function statement2($id)
    {

        $customer = customer::where('id', $id)->first();
        if (!$customer) {
            abort(404);  // This will show the 404 page
        }
        // order by date asc
        $customer_ledger_info = CustomerLedger::where('customer_id', $id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $products = CustomerTransactionDetails::where('customer_id', $id)->get();
        return view('admin.customer.statement2', get_defined_vars());
    }

    // customer ledger view

    public function ledgerView()
    {
        return view('admin.customer.customer_ledger');
    }

    public function viewLedger()
    {
        return view('admin.customer.test');
    }

    public function exportCustomer(Request $request, string $type = 'all', string $format = 'excel'){

        if ($request->has('q')) {
            $queryString = $request->q;
        } else {
            $queryString = "";
        }

        $query = customer::with(['ledgers' => function ($query) {
                $query->where('type', ['sale', 'return', 'collection', 'other']);
            }])
            ->withSum(['ledgers as total_sales' => function ($query) {
                $query->where('type', 'sale');
            }], 'total_price')
            ->withSum(['ledgers as total_price_discounts' => function ($query) {
                $query->where('type', 'sale');
            }], 'price_discount')
            ->withSum(['ledgers as total_vat' => function ($query) {
                $query->where('type', 'sale');
            }], 'vat')
            ->withSum(['ledgers as total_carring' => function ($query) {
                $query->where('type', 'sale');
            }], 'carring')
            ->withSum(['ledgers as total_others' => function ($query) {
                $query->where('type', 'sale');
            }], 'other_charge')
            ->withSum(['ledgers as total_returns' => function ($query) {
                $query->where('type', 'return');
            }], 'total_price')
            ->withSum(['ledgers as total_collections' => function ($query) {
                $query->where('type', 'sale')->orwhere('type', 'other')->orwhere('type', 'collection');
            }], 'payment')
            ->withSum(['ledgers as previous_due' => function ($query) {
                $query->where('type', 'other')->where('balance', '>', 0);
            }], 'balance')
            ->where(function ($query) use ($queryString) {
                $query->where('name', 'LIKE', "%$queryString%")
                ->orWhere('address', 'LIKE', "%$queryString%")
                ->orWhere('mobile', 'LIKE', "%$queryString%")
                ->orWhere('id', $queryString);
            })
            ->orderBy('id', 'asc');

        if ($type == 'due') {
            $customers = $query->where('balance', '>', 0)->get();
        } else {
            $customers = $query->get();
        }

        $customerIds = $customers->pluck('id')->toArray();
        $tranx_all = CustomerTransactionDetails::whereIn('customer_id', $customerIds)->get();
        $group_by_customer = $tranx_all->groupBy('customer_id')->map(function ($items) {
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
                        'sale' => 0,
                    ];
                }

                // Accumulate the quantities for each transaction type
                if ( $transactionType == 'sale' ) {
                    $totals[$productType]['sale'] += $quantity;
                    if ($discount_quantity > 0){
                        $totals[$productType]['discount'] += $discount_quantity;
                    }
                } elseif ( $transactionType == 'return' ) {
                    $totals[$productType]['return'] += $quantity;
                }
            });

            return $totals;
        });
        $file_name = 'cuutomer-list.pdf';
        $columns = [
            'id' => 'SL',
            'name' => 'Customer Name',
            'address' => 'Address',
            'phone' => 'Mobile',
            'ledger' => 'Ledger',
            'price_group' => 'Price Group',
            'type' => 'Type',
            'credit_limit' => 'Credit Limit',
            'quantity' => 'Quantity',
            'discount_qty' => 'Dis. Qty',
            'return_qty' => 'Return Qty',
            'sale_qty' => 'Sale Qty',
            'sale_amount' => 'Sale Amount',
            'return' => 'Return',
            'discount' => 'Discount',
            'carring' => 'Carring',
            'others' => 'Others',
            // 'vat' => 'VAT',
            'total' => 'Total (Tk)',
            'collection' => 'Collection',
            'balance' => 'Balance (Tk)',
        ];
        if ( $type == 'due' ) {
            $file_name = 'customer-due-list.pdf';
            $columns = [
                'id' => 'SL',
                'name' => 'Customer Name',
                'address' => 'Address',
                'phone' => 'Mobile',
                'ledger' => 'Ledger',
                'type' => 'Type',
                'sale_qty' => 'Sale Qty',
                'total' => 'Total (Tk)',
                'collection' => 'Collection',
                'balance' => 'Balance (Tk)',
            ];
        }

        if($format == 'excel'){
            $file_name = 'customer-list.xlsx';
            if($type == 'due'){
                $file_name = 'customer-due-list.xlsx';
            }
            return Excel::download(new CustomersExport($customers, $group_by_customer, $columns, $type), $file_name, \Maatwebsite\Excel\Excel::XLSX);
        } else if($format == 'csv'){
            $file_name = 'customer-list.csv';
            if($type == 'due'){
                $file_name = 'customer-due-list.csv';
            }
            return Excel::download(new CustomersExport($customers, $group_by_customer, $columns, $type), $file_name, \Maatwebsite\Excel\Excel::CSV);
        } else if($format == 'pdf'){
            $pdf = PDF::loadView('admin.customer.pdf.all', ['customers' => $customers, 'group_by_customer' => $group_by_customer, 'columns' => $columns, 'type' => $type]);
            $pdf->setOption(['dpi' => 100, 'defaultFont' => 'sans-serif']);
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            return $pdf->stream($file_name);
        }
    }
}
