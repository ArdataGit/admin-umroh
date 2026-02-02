<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produks = [
            [
                'kode_produk' => 'PRD-001',
                'nama_produk' => 'Kain Ihram',
                'standar_stok' => 50,
                'aktual_stok' => 100,
                'satuan_unit' => 'Pcs',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
                'catatan_produk' => 'Bahan katun berkualitas'
            ],
            [
                'kode_produk' => 'PRD-002',
                'nama_produk' => 'Sabuk Haji',
                'standar_stok' => 30,
                'aktual_stok' => 45,
                'satuan_unit' => 'Pcs',
                'harga_beli' => 25000,
                'harga_jual' => 40000,
                'catatan_produk' => 'Ukuran adjustable'
            ],
            [
                'kode_produk' => 'PRD-003',
                'nama_produk' => 'Mukena Travel',
                'standar_stok' => 20,
                'aktual_stok' => 25,
                'satuan_unit' => 'Set',
                'harga_beli' => 120000,
                'harga_jual' => 180000,
                'catatan_produk' => 'Ringan dan mudah dibawa'
            ],
        ];

        foreach ($produks as $produk) {
            Produk::create($produk);
        }
    }
}
