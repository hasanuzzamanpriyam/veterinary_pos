<?php

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$stores = \App\Models\ProductStore::with(['store'])->get();
foreach ($stores as $store) {
    print_r([
        'purchase_date' => $store->created_at->format('d-m-Y'),
        'store_name' => $store->store ? $store->store->name : '',
        'quantity' => $store->product_quantity,
        'purchase_rate' => $store->purchase_price,
    ]);
}
