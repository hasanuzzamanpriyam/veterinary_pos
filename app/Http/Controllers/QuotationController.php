<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;

class QuotationController extends Controller
{
    //show quotation list from here
    public function index()
    {
        $quotations = Quotation::orderBy('id','DESC')->get();

        return view('admin.quotation.index',get_defined_vars());
    }
    //create customer quotation from here
    public function create()
    {
        return view('admin.quotation.create');
    }

    public function view($id)
    {
        $quotation = Quotation::where('id',$id)->first();
        return view('admin.quotation.view',get_defined_vars());

    }






}
