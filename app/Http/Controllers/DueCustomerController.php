<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\PriceGroup;
use App\Models\CustomerLedger;
use App\Models\CustomerTypes;

use Illuminate\Http\Request;

class DueCustomerController extends Controller
{
    //

    public function index()
    {

        $customers = customer::where('balance', '>', '0')->latest()->get();
        $customer_types = CustomerTypes::latest()->get();
        $ledger = customer::with(['ledgers' => function ($query) {
            $query->whereIn('type', ['sale', 'return', 'collection', 'other']);
        }])
            ->withSum(['ledgers as total_sales' => function ($query) {
                $query->where('type', 'sale');
            }], 'total_price')
            ->withSum(['ledgers as total_price_discounts' => function ($query) {
                $query->where('type', 'sale');
            }], 'price_discount')
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
                $query->whereIn('type', ['sale', 'other', 'collection']);
            }], 'payment')
            ->withSum(['ledgers as total_sale_qty' => function ($query) {
                $query->where('type', 'sale');
            }], 'total_qty')
            ->withSum(['ledgers as total_return_qty' => function ($query) {
                $query->where('type', 'return');
            }], 'total_qty')
            ->withSum(['ledgers as total_sale_discount_qty' => function ($query) {
                $query->where('type', 'sale');
            }], 'product_discount')
            ->get();

        return view('admin.due_customer.index', get_defined_vars());
    }
}
