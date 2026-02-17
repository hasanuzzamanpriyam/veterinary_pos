<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->get();
        return view('admin.unit.index',compact('units'));
    }

    public function create()
    {
        return view('admin.unit.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => ['max:1000'],
            'remarks' => ['max:255'],
           
        ]);

        Unit::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Unit Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('unit.index')->with($alert);


    }
    public function edit($id)
    {
        $unit = Unit::where('id',$id)->first();

        return view('admin.unit.edit', compact('unit'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],
           
        ]);
        
        Unit::where('id',$request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Unit Successfully Update', 'alert-type' => 'info');
        return redirect()->route('unit.index')->with($alert);


    }

    public function delete($id)
    {
       
        Unit::where('id',$id)->delete();

        $alert = array('msg' => 'Unit Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('unit.index')->with($alert);
    }
}
