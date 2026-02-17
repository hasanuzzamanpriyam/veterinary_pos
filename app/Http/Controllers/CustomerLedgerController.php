<?php

namespace App\Http\Controllers;

use App\Models\CustomerLedger;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerLedgerController extends Controller
{

    public function index(){

    }

    public function ledger($id){
        
        $customer = customer::where('id',$id)->first();

        $customer_ledger_info = CustomerLedger::where('customer_id',$id)->get();

        return view('admin.customer.ledger',get_defined_vars());
    }
}
