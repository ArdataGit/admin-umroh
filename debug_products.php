<?php

use App\Models\Produk;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$allProducts = Produk::get(['id', 'nama_produk', 'aktual_stok', 'standar_stok']);
$lowStock = Produk::whereRaw('aktual_stok < standar_stok')->get();
$emptyStock = Produk::where('aktual_stok', 0)->get();

echo "Total Products: " . $allProducts->count() . "\n";
echo "Low Stock Products (< standar): " . $lowStock->count() . "\n";
echo "Empty Stock Products (== 0): " . $emptyStock->count() . "\n";

echo "All Products Sample:\n";
foreach($allProducts->take(5) as $p) {
    echo " - {$p->nama_produk}: Actual={$p->aktual_stok}, Standard={$p->standar_stok}\n";
}
