<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use App\Models\Category;
use App\Models\PriceGroup;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Size;
use App\Models\Stack;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\ProductStockReport;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    //

    public function index(){

        $products = Product::latest()->get();

        $product_groups = ProductGroup::get();
        $categories = Category::get();
        $warehouses = Warehouse::get();
        $units = Unit::get();
        $brands = Brand::get();
        $sizes = Size::get();

        return view('admin.product_stock_report.index', get_defined_vars());

    }

    // public function create(){

    //     return view('admin.product_stock_report.create');
    // }
}
