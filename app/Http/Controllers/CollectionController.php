<?php

namespace App\Http\Controllers;

use App\Models\CustomerLedger;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections_list = CustomerLedger::where('payment', '>', 0)->orderBy('date','DESC')->orderBy('id','DESC')->get();
        return view('admin.account.collection.index', get_defined_vars());
    }

    // view details collection from here
    public function view($id)
    {
        $invoice = CustomerLedger::where('id',$id)->first();
        return view('admin.account.collection.view', compact('invoice'));
    }

    public function print($id){
        $invoice = CustomerLedger::where('id',$id)->first();
        return view('admin.account.collection.print', compact('invoice'));

    }

    //get collection report from here
    public function report()
    {
        return view('admin.account.collection.report');
    }

    public function collectionMemoSearch()
    {
        return view('admin.account.collection.memo-search');
    }

    public function collectionMemoSearched(Request $request)
    {
        $memoNumber = $request->input('collection_memo_no');

        $collectionMemo = CustomerLedger::where('type', 'collection')
            ->where('id', $memoNumber)->first();
        return view('admin.account.collection.memo-search', get_defined_vars());
    }
}
