<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index',compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => ['max:255'],
            'description' => ['max:1000'],
            'remarks' => ['max:255'],
           
        ]);

        Category::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Category Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('category.index')->with($alert);


    }
    public function edit($id)
    {
        $category = Category::where('id',$id)->first();

        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => ['max:255'],
            'description' => ['max:1000'],
            'remarks' => ['max:255'],
           
        ]);
        
        Category::where('id',$request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Category Successfully Update', 'alert-type' => 'info');
        return redirect()->route('category.index')->with($alert);


    }

    public function delete($id)
    {
       
        Category::where('id',$id)->delete();

        $alert = array('msg' => 'Category Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('category.index')->with($alert);
    }


}
