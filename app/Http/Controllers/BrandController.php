<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('admin.brand.index',compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => ['max:1000'],
            'remarks' => [ 'max:255'],
           
        ]);

        Brand::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Brand Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('brand.index')->with($alert);


    }
    public function edit($id)
    {
        $brand = Brand::where('id',$id)->first();

        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => ['max:1000'],
            'remarks' => [ 'max:255'],
           
        ]);
        
        Brand::where('id',$request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Brand Successfully Update', 'alert-type' => 'info');
        return redirect()->route('brand.index')->with($alert);


    }

    public function delete($id)
    {
       
        Brand::where('id',$id)->delete();

        $alert = array('msg' => 'Brand Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('brand.index')->with($alert);
    }
}
