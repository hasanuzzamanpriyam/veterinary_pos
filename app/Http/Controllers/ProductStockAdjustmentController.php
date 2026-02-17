<?php

namespace App\Http\Controllers;

use App\Models\customer;
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
use App\Models\Store;
use App\Models\PriceGroupProduct;
use App\Models\ProductStockAdjustments;

use Illuminate\Http\Request;

class ProductStockAdjustmentController extends Controller
{

    public function index() {}

    public function create()
    {

        $customers = Customer::latest()->get();
        $stores = Store::latest()->get();
        $products = Product::latest()->get();
        $warehouses = Warehouse::get();
        $priceGroupProduct = PriceGroupProduct::latest()->first();

        // dd($products);
        // dd($warehouses);
        // dd($priceGroupProduct);



        return view('admin.productstockadjustment.create', get_defined_vars());
    }
}
