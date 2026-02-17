<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PurchaseProduct;
use App\Models\PriceGroup;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Size;
use App\Models\Stack;
use App\Models\SubCategory;
use App\Models\Unit;

use App\Models\ProductStore;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->get();
        return view('admin.warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        return view('admin.warehouse.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => ['max:255'],
            'address' => ['max:1000'],
            'mobile' => ['max:20'],
            'phone' => ['max:20'],
            'description' => ['max:1000'],
            'remarks' => ['max:255'],

        ]);

        Warehouse::insert([
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status == false ? '1' : '0',
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Warehouse Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('warehouse.index')->with($alert);
    }

    public function edit($id)
    {
        $warehouse = Warehouse::where('id', $id)->first();

        return view('admin.warehouse.edit', compact('warehouse'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => ['max:255'],
            'address' => ['max:1000'],
            'mobile' => ['max:20'],
            'phone' => ['max:20'],
            'description' => ['max:1000'],
            'remarks' => ['max:255'],

        ]);

        Warehouse::where('id', $request->id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status == true ? '1' : '0',
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Warehouse Successfully Update', 'alert-type' => 'info');
        return redirect()->route('warehouse.index')->with($alert);
    }

    public function delete($id)
    {

        Warehouse::where('id', $id)->delete();

        $alert = array('msg' => 'Warehouse Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('warehouse.index')->with($alert);
    }


    public function status($id, $status)
    {


        Warehouse::where('id', $id)->update(['status' => $status == 1 ? 0 : 1]);

        $alert = array('msg' => 'Warehouse Status Successfully Updated', 'alert-type' => 'info');
        return redirect()->route('warehouse.index')->with($alert);
    }
}
