<?php

namespace App\Http\Controllers;

use App\Models\ProductStore;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::latest()->get();
        return view('admin.store.index',compact('stores'));
    }

    public function create()
    {
        return view('admin.store.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => [  'max:255'],
            'address' => [ 'max:1000'],
            'mobile' => ['max:20'],
            'phone' => ['max:20'],
            'email' => ['max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [  'max:255'],
        ]);

        Store::insert([
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 1,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Store Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('store.index')->with($alert);


    }
    public function edit($id)
    {
        $store = Store::where('id',$id)->first();

        return view('admin.store.edit', compact('store'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => [  'max:255'],
            'address' => [ 'max:1000'],
            'mobile' => ['max:20'],
            'phone' => ['max:20'],
            'email' => ['max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [  'max:255'],

        ]);

        Store::where('id',$request->id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Store Successfully Update', 'alert-type' => 'info');
        return redirect()->route('store.index')->with($alert);

    }

    public function status($id, $status)
    {

        Store::where('id',$id)->update(['status' => $status == 1 ? 0 : 1]);
        $alert = array('msg' => 'Store Status Successfully Updated', 'alert-type' => 'info');
        return redirect()->route('store.index')->with($alert);

    }

    public function delete($id)
    {

        Store::where('id',$id)->delete();

        $alert = array('msg' => 'Store Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('store.index')->with($alert);
    }
}
