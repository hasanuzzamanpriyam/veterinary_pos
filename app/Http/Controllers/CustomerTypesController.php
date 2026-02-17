<?php

namespace App\Http\Controllers;

use App\Models\CustomerTypes;
use Illuminate\Http\Request;

class CustomerTypesController extends Controller
{
    //

    public function index()
    {
        $customer_types = CustomerTypes::latest()->get();
        return view('admin.customer_types.index',get_defined_vars());
    }

    public function create(){
        return view("admin.customer_types.create");
    }

    public function store(Request $request){

        $validator = $request->validate([
            'name' => [ 'required','max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],

        ]);

        CustomerTypes::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);


        $alert = array('msg' => ' Customer Type Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('customer_type.index')->with($alert);

    }


    public function edit($id){

        $customer_types = CustomerTypes::where("id",$id)->first();

        return view("admin.customer_types.edit",get_defined_vars());
    }


    public function update(Request $request){

        $validator = $request->validate([
            'name' => [ 'required','max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],

        ]);

        // dd($request->id);

        CustomerTypes::where('id',$request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);


        $alert = array('msg' => ' Customer Type Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('customer_type.index')->with($alert);
    }


    public function delete($id)
    {

        CustomerTypes::where('id',$id)->delete();
        $alert = array('msg' => 'Size Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('customer_type.index')->with($alert);
    }

}
