<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStore;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $categories = Category::all();

        // Get paginated products
        $products = Product::with(['category', 'brand', 'size'])
            ->orderBy('id', 'desc')
            ->paginate(12); // Show 12 products per page

        $this->processProducts($products);

        return view('products', compact('products', 'categories', 'setting'));
    }

    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);

        $products = Product::with(['category', 'brand', 'size'])
            ->orderBy('id', 'desc')
            ->paginate(12, ['*'], 'page', $page);

        $this->processProducts($products);

        return response()->json([
            'products' => $products->items(),
            'hasMore' => $products->hasMorePages(),
            'nextPage' => $products->currentPage() + 1
        ]);
    }

    private function processProducts($products)
    {
        $productIds = $products->pluck('id')->toArray();

        $stocks = ProductStore::whereIn('product_id', $productIds)->get();
        $stockQuantities = $stocks->groupBy('product_id')->map(function ($items) {
            return $items->sum('product_quantity');
        });

        foreach ($products as $product) {
            $totalStock = $stockQuantities->get($product->id, 0);
            $product->total_stock = $totalStock;
            $product->stock_status = $totalStock > 0 ? 'In Stock' : 'Out of Stock';

            $priceInfo = $product->priceWithOffer($product->mrp_rate);
            $product->display_price = $priceInfo['price'];
            $product->has_offer = $priceInfo['offer'] !== null;
            $product->original_price = $product->mrp_rate;

            $product->image_path = $product->photo && file_exists(public_path($product->photo))
                ? asset($product->photo)
                : asset('assets/images/product-placeholder.jpg');
        }
    }
}
