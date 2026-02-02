<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanans = [
            [
                'kode_layanan' => 'SVC-001',
                'jenis_layanan' => 'Visa',
                'nama_layanan' => 'Visa Umroh Saudi',
                'satuan_unit' => 'Pax',
                'harga_modal' => 2500000,
                'harga_jual' => 3000000,
                'status_layanan' => 'Active',
                'catatan_layanan' => 'Proses 7 Hari Kerja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_layanan' => 'SVC-002',
                'jenis_layanan' => 'Transport',
                'nama_layanan' => 'Bus AC Executive Madinah-Makkah',
                'satuan_unit' => 'Seat',
                'harga_modal' => 150000,
                'harga_jual' => 250000,
                'status_layanan' => 'Active',
                'catatan_layanan' => 'Perjalanan darat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_layanan' => 'SVC-003',
                'jenis_layanan' => 'Handling',
                'nama_layanan' => 'Handling Bandara Jakarta',
                'satuan_unit' => 'Pax',
                'harga_modal' => 300000,
                'harga_jual' => 500000,
                'status_layanan' => 'Active',
                'catatan_layanan' => 'Termasuk lounge',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_layanan' => 'SVC-004',
                'jenis_layanan' => 'Tour',
                'nama_layanan' => 'City Tour Turki',
                'satuan_unit' => 'Pax',
                'harga_modal' => 1000000,
                'harga_jual' => 1500000,
                'status_layanan' => 'Active',
                'catatan_layanan' => '1 Hari Full',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_layanan' => 'SVC-005',
                'jenis_layanan' => 'Layanan',
                'nama_layanan' => 'Badal Umroh',
                'satuan_unit' => 'Pax',
                'harga_modal' => 2000000,
                'harga_jual' => 3500000,
                'status_layanan' => 'Active',
                'catatan_layanan' => 'Sertifikat included',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('layanans')->insert($layanans);
    }
}
