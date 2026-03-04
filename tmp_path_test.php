<?php

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$filename1 = 'foo.jpg';
$path1 = 'storage/' . $filename1;
echo 'Path 1: ' . asset($path1) . "\n";
echo 'Path 1 with leading slash: ' . asset('/' . $path1) . "\n";
echo 'Path 1 with multiple slashes: ' . asset('//storage//images/product/foo.jpg') . "\n";
