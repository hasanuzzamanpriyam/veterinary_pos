<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index()
    {
        $product_types = ProductType::all();
        return view('admin.product_type.index', compact('product_types'));
    }

    public function create()
    {
        return view('admin.product_type.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:product_types,name',
        ]);

        ProductType::create($request->all());

        return redirect()->route('product_type.index')->with('success', 'Product Type created successfully.');
    }

    public function edit($id)
    {
        $product_type = ProductType::findOrFail($id);
        return view('admin.product_type.edit', compact('product_type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:product_types,name,' . $id,
        ]);

        $product_type = ProductType::findOrFail($id);
        $product_type->update($request->all());

        return redirect()->route('product_type.index')->with('success', 'Product Type updated successfully.');
    }

    public function destroy($id)
    {
        $product_type = ProductType::findOrFail($id);
        $product_type->delete();

        return redirect()->route('product_type.index')->with('success', 'Product Type deleted successfully.');
    }
}
