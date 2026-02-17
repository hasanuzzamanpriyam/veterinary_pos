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
use App\Models\ProductStockAdjustments;
use App\Models\ProductStore;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()
        ->orderby('code', 'asc')
        ->with(['brand','category','productGroup','size'])
        ->get();

        // Attach active offer and computed sale price with offer to each product to avoid querying in the view
        foreach ($products as $product) {
            $product->active_offer = $product->activeOffer();
            $product->sale_price_with_offer = $product->priceWithOffer($product->price_rate)['price'];
        }
        $stocks = ProductStore::get();
        $mergedProducts = $stocks->groupBy('product_id')->map(function ($items) {
            return [
                'qty' => $items->sum('product_quantity')
            ];
        });
        $stock_list = $mergedProducts;

        return view('admin.product.index',get_defined_vars());
    }

    public function create()
    {
        return view('admin.product.create');
    }


    public function checkout(){
        return view('admin.product.checkout');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'code' => [ 'max:255'],
            'name' => [ 'max:255'],
            'group_id' => ['max:11'],
            'purchase_rate' => ['max:20'],
            'price_rate' => ['max:20'],
            'mrp_rate' => ['max:20'],
            // 'opening_stock' => ['max:10'],
            'alert_quantity' => ['max:10'],
            'photo' => ['image','mimes:jpeg,png,jpg,gif,svg','max:1000'],

        ]);
        // dd($validator);

        if(!empty($request->photo)) {
            $photo = $request->photo;
            $photoName = uniqid().'.'.$photo->getClientOriginalExtension();
            $photo_path = $photo->move('images/product/',$photoName);
        } else {
            $photo_path = "";
        }

      $product = Product::insertGetId([
            'code' => $validator['code'],
            'name' => $validator['name'],
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'size_id' => $request->size_id,
            'unit_id' => $request->unit_id,
            'barcode' => $request->barcode,
            'group_id' => $validator['group_id'],
            'purchase_rate' => $validator['purchase_rate'],
            'price_rate' => $validator['price_rate'],
            'mrp_rate' => $validator['mrp_rate'],
            'alert_quantity' => $validator['alert_quantity'],
            'warehouse_id' => $request->warehouse_id,
            'status' => $request->status == true ? '1':'0',
            'remarks' => $request->remarks,
            'photo' => $photo_path,

        ]);

        $alert = array('msg' => 'Product Successfully Inserted', 'alert-type' => 'success');
        return redirect()->route('product.view', $product )->with($alert);
    }


    public function edit($id)
    {
        $product_groups = ProductGroup::get();
        $product = Product::where('id',$id)->first();
        $categories = Category::get();
        $brands = Brand::get();
        $sizes = Size::get();

        return view('admin.product.edit',get_defined_vars());
    }

    public function update(Request $request)
    {

        $request->validate([
            'code' => [ 'max:255'],
            'name' => [ 'max:255'],
            'group_id' => ['max:11'],
            'purchase_rate' => ['max:20'],
            'price_rate' => ['max:20'],
            'mrp_rate' => ['max:20'],
            'alert_quantity' => ['max:10'],
            'photo' => ['image','mimes:jpeg,png,jpg,gif,svg','max:2048'],

        ]);

        // dd($request->photo);

        if(!empty($request->photo)) {
            if ($request->old_photo) {
                Storage::disk('public')->delete($request->old_photo);
            }

            $photo_path = $request->photo->store('/images/product', 'public');
        } else {
            if(!empty($request->old_photo)) {
                $photo_path = $request->old_photo;
            } else {
                $photo_path = "";
            }
        }



        Product::where('id',$request->id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'size_id' => $request->size_id,
            'barcode' => $request->barcode,
            'group_id' => $request->group_id,
            'purchase_rate' => $request->purchase_rate,
            'price_rate' => $request->price_rate,
            'mrp_rate' => $request->mrp_rate,
            'alert_quantity' => $request->alert_quantity,
            'remarks' => $request->remarks,
            'photo' => !empty($photo_path) ? '/storage/' . $photo_path : '',
        ]);

        $alert = array('msg' => 'Product Successfully Updated', 'alert-type' => 'info');
        return redirect()->route('product.view', $request->id)->with($alert);

    }

    public function delete($id)
    {
        $stock_data = ProductStore::where('product_id',$id)->get();
        $mergedProducts = $stock_data->groupBy('product_id')->map(function ($items) {
            return [
                'qty' => $items->sum('product_quantity')
            ];
        });

        if( count($stock_data) > 0 && $mergedProducts[$id]['qty'] > 0){
            $alert = array('msg' => 'Product Stock Exists. Please Delete Stock First', 'alert-type' => 'warning');
            return redirect()->route('product.index')->with($alert);
        }
        $getImg = Product::where('id',$id)->first();
        if(file_exists($getImg->photo ))
        {
            unlink($getImg->photo);

        }

        Product::where('id',$id)->delete();


        $alert = array('msg' => 'Product Successfully Deleted', 'alert-type' => 'warning');
        return redirect()->route('product.index')->with($alert);
    }

    public function view($id)
    {
        $product = Product::where('id',$id)->first();
        $stock_data = ProductStore::where('product_id',$id)->get();
        // dump($stock_data);
        $store_data = $stock_data->groupBy('product_store_id')->map(function ($items) use ($product) {
            // dd($items);
            return [
                'name' => $items->first()->store->name,
                'qty' => $items->sum('product_quantity'),
                'weight' => ($product->size->name * $items->sum('product_quantity')) / 1000,
                'price' => $items->sum('product_quantity') * $product->purchase_rate,
                'sale_value' => $items->sum('product_quantity') * $product->price_rate
            ];
        });
        // dd($store_data);

        $stock = $stock_data->groupBy('product_id')->map(function ($items) use ($product) {
            // dump($items);
            return [
                'qty' => $items->sum('product_quantity'),
                'price' => $items->reduce(function ($carry, $item) {
                    return ($item['product_quantity'] * $item['purchase_price']);
                }, 0),
                'sale_value' => $items->sum('product_quantity') * $product->price_rate
            ];
        });
        // dd( $stock);
        return view('admin.product.view',compact('product', 'stock_data', 'store_data', 'stock'));
    }

    public function gallery()
    {
        $products = Product::latest()->get();
        return view('admin.product.gallery', get_defined_vars());

    }

    public function search(Request $request){

    }

}
