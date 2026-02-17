<?php

namespace App\Http\Controllers;

use App\Models\PriceGroup;
use App\Models\PriceGroupProduct;
use App\Models\Product;
use App\Models\ProductStockAdjustments;
use Illuminate\Http\Request;

class PriceGroupController extends Controller
{
    public function index()
    {
        $price_groups = PriceGroup::latest()->get();
        return view('admin.price_group.index',compact('price_groups'));
    }

    public function create()
    {
        return view('admin.price_group.create');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => [  'max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],

        ]);

        PriceGroup::insert([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Price Group Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('price_group.index')->with($alert);


    }
    public function edit($id)
    {
        $price_group = PriceGroup::where('id',$id)->first();

        return view('admin.price_group.edit', compact('price_group'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'name' => [ 'max:255'],
            'description' => [ 'max:1000'],
            'remarks' => [ 'max:255'],

        ]);

        PriceGroup::where('id',$request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'remarks' => $request->remarks,
        ]);

        $alert = array('msg' => 'Price Group Successfully Update', 'alert-type' => 'info');
        return redirect()->route('price_group.index')->with($alert);


    }

    // add price group product
    public function add($id)
    {
        $price_group = PriceGroup::where('id',$id)->first();
        $products = Product::latest()->orderby('code', 'asc')->get();


        return view('admin.price_group.add', get_defined_vars());

    }
     //   price group wise product store
    public function storeProduct(Request $request)
    {

        //dd($request->all());

        $request->validate([
            'price_group_id' => 'required',
            'product_id' => 'required',
            'price_group_rate' => 'required',
        ]);

        $product_id = $request->product_id;
        $price_group_rate = array_values(array_filter(array_map('trim',  $request->price_group_rate), 'strlen'));

       //dd( $product_id,  $price_group_rate);


       for($i = 0; $i< count($product_id); $i++)
       {

        PriceGroupProduct::updateOrCreate(
                    ['price_group_id' =>$request->price_group_id ,'product_id' =>$product_id[$i]] ,
                        ['price_group_rate' =>$price_group_rate[$i]]
                );
       }
        // foreach( $groups as $value)
        // {
        //     PriceGroupProduct::updateOrCreate(
        //         ['price_group_id' =>$request->group_id , 'product_id' =>$value->product_id ],
        //         ['group_price_rate' =>$value->price_group_rate]
        //     );
        // }

        $alert = array('msg' => 'Price Group Product Successfully Inserted', 'alert-type' => 'success');
        return redirect()->back()->with($alert);

    }

    public function show($id)
    {
        $products = PriceGroupProduct::where('price_group_id',$id)->get();
        return view('admin.price_group.show', get_defined_vars());
    }

    public function delete($id)
    {

        PriceGroup::where('id',$id)->delete();

        $alert = array('msg' => 'Price Group Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('price_group.index')->with($alert);
    }
}
