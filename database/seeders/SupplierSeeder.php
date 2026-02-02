<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'kode_supplier' => 'SUP-001',
                'nama_supplier' => 'PT. Barokah Abadi',
                'kontak_supplier' => '081234567890',
                'email_supplier' => 'admin@barokahabadi.com',
                'kota_provinsi' => 'Jakarta Selatan, DKI Jakarta',
                'alamat_supplier' => 'Jl. Fatmawati No. 12',
                'catatan_supplier' => 'Supplier perlengkapan haji dan umroh (kain ihram, sabuk, dll)',
            ],
            [
                'kode_supplier' => 'SUP-002',
                'nama_supplier' => 'CV. Amanah Catering',
                'kontak_supplier' => '081987654321',
                'email_supplier' => 'info@amanahcatering.id',
                'kota_provinsi' => 'Bekasi, Jawa Barat',
                'alamat_supplier' => 'Ruko Grand Galaxy City Blok RGA No. 5',
                'catatan_supplier' => 'Supplier makanan dan snack untuk manasik',
            ],
            [
                'kode_supplier' => 'SUP-003',
                'nama_supplier' => 'Toko Souvenir Makkah',
                'kontak_supplier' => '085612341234',
                'email_supplier' => 'souvenir@makkahshop.com',
                'kota_provinsi' => 'Surabaya, Jawa Timur',
                'alamat_supplier' => 'Pasar Ampel No. 88',
                'catatan_supplier' => 'Supplier oleh-oleh haji (air zam-zam, kurma, kismis)',
            ],
            [
                'kode_supplier' => 'SUP-004',
                'nama_supplier' => 'Garment Haji Sejahtera',
                'kontak_supplier' => '081211223344',
                'email_supplier' => 'sales@garmenthaji.com',
                'kota_provinsi' => 'Bandung, Jawa Barat',
                'alamat_supplier' => 'Jl. Cigondewah Kaler No. 45',
                'catatan_supplier' => 'Produksi seragam batik dan koper',
            ],
            [
                'kode_supplier' => 'SUP-005',
                'nama_supplier' => 'Percetakan Risalah',
                'kontak_supplier' => '081399887766',
                'email_supplier' => 'order@percetakanrisalah.com',
                'kota_provinsi' => 'Yogyakarta, DIY',
                'alamat_supplier' => 'Jl. Malioboro No. 10',
                'catatan_supplier' => 'Cetak buku panduan manasik dan ID Card',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
