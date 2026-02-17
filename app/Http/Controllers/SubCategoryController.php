<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::latest()->get();
        return view('admin.subcategory.index',get_defined_vars());
    }

    public function create()
    {
        $categories = Category::get();
        return view('admin.subcategory.create',get_defined_vars());
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],
           
        ]);

        SubCategory::insert([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Sub Category Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('subcategory.index')->with($alert);

    }
    public function edit($id)
    {
        $categories = Category::get();
        $subcategory = SubCategory::where('id',$id)->first();

        return view('admin.subcategory.edit',get_defined_vars());
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],
           
        ]);
        
        SubCategory::where('id',$request->id)->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Sub Category Successfully Update', 'alert-type' => 'info');
        return redirect()->route('subcategory.index')->with($alert);


    }

    public function delete($id)
    {
       
        SubCategory::where('id',$id)->delete();

        $alert = array('msg' => 'Sub Category Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('subcategory.index')->with($alert);
    }
}
