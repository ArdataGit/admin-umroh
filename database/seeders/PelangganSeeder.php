<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = [
            [
                'kode_pelanggan' => 'CUST-001',
                'nama_pelanggan' => 'PT Sinar Jaya Abadi',
                'kontak_pelanggan' => '081234567890',
                'email_pelanggan' => 'info@sinarjaya.com',
                'kabupaten_kota' => 'Jakarta Selatan',
                'jenis_kelamin' => 'Laki-laki',
                'status_pelanggan' => 'Active',
                'alamat_pelanggan' => 'Jl. Sudirman No. 45, Jakarta',
                'catatan_pelanggan' => 'Pelanggan VIP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan' => 'CUST-002',
                'nama_pelanggan' => 'CV Maju Mundur',
                'kontak_pelanggan' => '081987654321',
                'email_pelanggan' => 'contact@majumundur.co.id',
                'kabupaten_kota' => 'Bandung',
                'jenis_kelamin' => 'Perempuan',
                'status_pelanggan' => 'Active',
                'alamat_pelanggan' => 'Jl. Asia Afrika No. 10, Bandung',
                'catatan_pelanggan' => 'Diskon member 10%',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan' => 'CUST-003',
                'nama_pelanggan' => 'Budi Santoso',
                'kontak_pelanggan' => '085612341234',
                'email_pelanggan' => 'budi.santoso@gmail.com',
                'kabupaten_kota' => 'Surabaya',
                'jenis_kelamin' => 'Laki-laki',
                'status_pelanggan' => 'Active',
                'alamat_pelanggan' => 'Jl. Tunjungan No. 5, Surabaya',
                'catatan_pelanggan' => 'Perorangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan' => 'CUST-004',
                'nama_pelanggan' => 'Siti Aminah',
                'kontak_pelanggan' => '081122334455',
                'email_pelanggan' => 'siti.aminah@yahoo.com',
                'kabupaten_kota' => 'Yogyakarta',
                'jenis_kelamin' => 'Perempuan',
                'status_pelanggan' => 'Non Active',
                'alamat_pelanggan' => 'Jl. Malioboro No. 12, Yogyakarta',
                'catatan_pelanggan' => 'Jarang transaksi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pelanggan' => 'CUST-005',
                'nama_pelanggan' => 'Koperasi Unit Desa',
                'kontak_pelanggan' => '082255667788',
                'email_pelanggan' => 'kud.sejahtera@desa.id',
                'kabupaten_kota' => 'Semarang',
                'jenis_kelamin' => 'Laki-laki',
                'status_pelanggan' => 'Active',
                'alamat_pelanggan' => 'Jl. Pemuda No. 100, Semarang',
                'catatan_pelanggan' => 'Mitra korporasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pelanggans')->insert($pelanggans);
    }
}
