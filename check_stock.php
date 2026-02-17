<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProductStore;
use App\Models\Product;

echo "=== STOCK CHECK ===" . PHP_EOL;
echo "Date: " . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

// Count products in stock
$stockCount = ProductStore::where('product_quantity', '>', 0)->count();
echo "Products in stock: {$stockCount}" . PHP_EOL . PHP_EOL;

if ($stockCount > 0) {
    // Get total stock value using purchase_price
    $totalPurchasePrice = ProductStore::where('product_quantity', '>', 0)
        ->selectRaw('SUM(product_quantity * purchase_price) as total')
        ->first()->total ?? 0;

    echo "Total Stock Value (purchase_price): " . number_format($totalPurchasePrice, 2) . " BDT" . PHP_EOL;

    // Get total stock value using purchase_rate
    $totalPurchaseRate = ProductStore::join('products', 'product_stores.product_id', '=', 'products.id')
        ->where('product_quantity', '>', 0)
        ->selectRaw('SUM(product_quantity * products.purchase_rate) as total')
        ->first()->total ?? 0;

    echo "Total Stock Value (purchase_rate): " . number_format($totalPurchaseRate, 2) . " BDT" . PHP_EOL . PHP_EOL;

    // Show sample products
    echo "Sample Products (first 10):" . PHP_EOL;
    echo str_repeat("-", 100) . PHP_EOL;
    printf("%-5s %-40s %-15s %-15s %-15s\n", "ID", "Product Name", "Quantity", "Purchase Price", "Purchase Rate");
    echo str_repeat("-", 100) . PHP_EOL;

    $samples = ProductStore::where('product_quantity', '>', 0)
        ->join('products', 'product_stores.product_id', '=', 'products.id')
        ->select('product_stores.*', 'products.product_name', 'products.purchase_rate')
        ->limit(10)
        ->get();

    foreach ($samples as $item) {
        printf(
            "%-5d %-40s %-15s %-15s %-15s\n",
            $item->id,
            substr($item->product_name ?? 'Unknown', 0, 40),
            number_format($item->product_quantity, 2),
            number_format($item->purchase_price, 2),
            number_format($item->purchase_rate, 2)
        );
    }
} else {
    echo "⚠️ No products in stock!" . PHP_EOL;
}

echo PHP_EOL . "=== END ===" . PHP_EOL;
