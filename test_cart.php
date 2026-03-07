<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

Cart::instance('test')->destroy();
Cart::instance('test')->add(1, 'Test', 1, 10, ['production_date' => null, 'expire_date' => null]);
$content = Cart::instance('test')->content();
$item = $content->first();
$item->options->put('production_date', '2026-03-07');
session()->put('cart.test', $content);

$retrievedContent = session()->get('cart.test');
$retrievedItem = $retrievedContent->first();

echo "Stored Options: " . json_encode($retrievedItem->options) . "\n";
