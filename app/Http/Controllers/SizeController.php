<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest()->get();
        return view('admin.size.index',compact('sizes'));
    }

    public function create()
    {
        return view('admin.size.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'required','integer'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],
           
        ]);

        Size::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Size Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('size.index')->with($alert);

    }
    public function edit($id)
    {
        $size = Size::where('id',$id)->first();

        return view('admin.size.edit', compact('size'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => ['required','integer'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],
           
        ]);
        
        Size::where('id',$request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Size Successfully Update', 'alert-type' => 'info');
        return redirect()->route('size.index')->with($alert);


    }

    public function delete($id)
    {
       
        Size::where('id',$id)->delete();
        $alert = array('msg' => 'Size Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('size.index')->with($alert);
    }
}
