<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/pengeluaran-produk', 'POST', [
    'kode_pengeluaran' => 'PK-999',
    'jamaah_id' => 1,
    'tanggal_pengeluaran' => '2026-02-21',
    'details' => [
        ['produk_id' => 1, 'quantity' => 1, 'harga_satuan' => 10000, 'total_harga' => 10000],
        ['produk_id' => 2, 'quantity' => 2, 'harga_satuan' => 20000, 'total_harga' => 40000]
    ],
    'tax_percentage' => 0,
    'discount_percentage' => 0,
    'shipping_cost' => 0,
    'total_nominal' => 50000,
    'status_pengeluaran' => 'process',
    'metode_pengiriman' => 'kantor',
    'alamat_pengiriman' => '',
    'catatan' => ''
]);
$request->headers->set('Accept', 'application/json');

$response = app()->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
