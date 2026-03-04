<?php

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$data = [
    'product_stores' => Schema::getColumnListing('product_stores'),
    'supplier_transaction_details' => Schema::getColumnListing('supplier_transaction_details'),
    'products' => Schema::getColumnListing('products'),
];

file_put_contents('tmp_schema.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Done";
