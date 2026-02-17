<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use Illuminate\Http\Request;

class ProductGroupController extends Controller
{
    public function index()
    {
        $product_groups = ProductGroup::latest()->get();
        return view('admin.product_group.index',compact('product_groups'));
    }

    public function create()
    {
        return view('admin.product_group.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => ['max:1000'],
            'remarks' => ['max:255'],
           
        ]);

        ProductGroup::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Product Group Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('product_group.index')->with($alert);


    }
    public function edit($id)
    {
        $product_group = ProductGroup::where('id',$id)->first();

        return view('admin.product_group.edit', compact('product_group'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => ['max:255'],
            'description' => ['max:1000'],
            'remarks' => ['max:255'],
           
        ]);
        
        ProductGroup::where('id',$request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Product Group Successfully Update', 'alert-type' => 'info');
        return redirect()->route('product_group.index')->with($alert);


    }

    public function delete($id)
    {
       
        ProductGroup::where('id',$id)->delete();

        $alert = array('msg' => 'Product Group Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('product_group.index')->with($alert);
    }
}
