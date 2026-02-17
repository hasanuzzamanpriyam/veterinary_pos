<?php

namespace App\Http\Controllers;

use App\Models\Designations;
use App\Models\Employee;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    //

    public function index(){

        $designations = Designations::latest()->get();
        return view('admin.designation.index',compact('designations'));
    }

    public function create(){
        return view('admin.designation.create');
    }

    public function store(Request $request){

        $validator = $request->validate([
            'designation_title' => ['required', 'max:255'],
            'designation_desc' => 'nullable|string',
        ]);

        // dd($request->all());

        Designations::insert([
            'designation_title' => $request->designation_title,
            'designation_desc' => $request->designation_desc,
        ]);

        $alert = array('msg' => 'Designation Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('designation.index')->with($alert);
    }

    public function edit($id){

        $designation = Designations::where('id',$id )->first();
        return view('admin.designation.edit',get_defined_vars());
    }

    public function update(Request $request){

        $validator = $request->validate([
            'designation_title' => ['required', 'max:255'],
            'designation_desc' => 'nullable|string',
        ]);

        Designations::where('id', $request->id )->update([

            'designation_title' => $request->designation_title,
            'designation_desc' => $request->designation_desc
        ]);


        $alert = array('msg' => 'Designation Successfully Updated', 'alert-type' => 'success');
        return redirect()->route('designation.index')->with($alert);
    }


    public function delete($id){

        Designations::where('id',$id)->delete();

        $alert = array('msg' => 'Size Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('designation.index')->with( $alert);
    }


}
