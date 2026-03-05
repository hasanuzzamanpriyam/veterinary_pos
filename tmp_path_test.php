<?php

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$storeData = \App\Models\ProductStore::with('store')->first();
echo "Product Store with relations:\n";
print_r($storeData->toArray());

$supplierDetails = \App\Models\SupplierTransactionDetails::first();
if ($supplierDetails) {
    echo "\nSupplierTransactionDetails schema:\n";
    print_r($supplierDetails->toArray());
}
