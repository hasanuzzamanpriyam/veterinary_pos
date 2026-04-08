<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductStore;
use App\Models\SupplierTransactionDetails;

$productStores = ProductStore::all();
$updated = 0;
foreach($productStores as $ps) {
    if ($ps->discount_quantity == 0) {
        $discountBought = SupplierTransactionDetails::where('product_id', $ps->product_id)
            ->where('product_store_id', $ps->product_store_id)
            ->sum('discount_qty');
        
        if ($discountBought > 0) {
            // make sure we don't exceed current product_quantity
            $ps->discount_quantity = min($discountBought, $ps->product_quantity);
            $ps->save();
            $updated++;
        }
    }
}
echo "Updated $updated product stores.\n";
