<?php

use Illuminate\Support\Facades\Facade;
use Gloudemans\Shoppingcart\Facades\Cart;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing Cart facade directly...\n";
    $count = Cart::instance('purchase')->count();
    echo "Cart count: " . $count . "\n";
    echo "Cart facade works!\n";
} catch (\Throwable $e) {
    echo "Error calling Cart::instance(): " . $e->getMessage() . "\n";
    echo "Exception class: " . get_class($e) . "\n";
}

try {
    echo "\nTesting app('cart') resolve...\n";
    $cart = app('cart');
    echo "Bound class: " . get_class($cart) . "\n";
    $count = $cart->instance('purchase')->count();
    echo "Cart count: " . $count . "\n";
    echo "app('cart') works!\n";
} catch (\Throwable $e) {
    echo "Error calling app('cart')->instance(): " . $e->getMessage() . "\n";
}
