<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jamaah;

class JamaahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jamaahs = [
            [
                'kode_jamaah' => 'JM-001',
                'nik_jamaah' => '3171010101800001',
                'nama_jamaah' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1980-05-15',
                'kontak_jamaah' => '081234567890',
                'email_jamaah' => 'budi.santoso@example.com',
                'kecamatan' => 'Gambir',
                'kabupaten_kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'alamat_jamaah' => 'Jl. Kebon Sirih No. 10',
                'alamat_lengkap' => 'Jl. Kebon Sirih No. 10, Gambir, Jakarta Pusat, DKI Jakarta',
                'catatan_jamaah' => 'Calon jamaah reguler',
                'nama_paspor' => 'BUDI SANTOSO',
                'nomor_paspor' => 'A1234567',
                'kantor_imigrasi' => 'Jakarta Pusat',
                'tgl_paspor_aktif' => '2025-01-01',
                'tgl_paspor_expired' => '2030-01-01',
            ],
            [
                'kode_jamaah' => 'JM-002',
                'nik_jamaah' => '3171014101850002',
                'nama_jamaah' => 'Siti Aminah',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1985-10-20',
                'kontak_jamaah' => '081345678901',
                'email_jamaah' => 'siti.aminah@example.com',
                'kecamatan' => 'Coblong',
                'kabupaten_kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'alamat_jamaah' => 'Jl. Dago No. 100',
                'alamat_lengkap' => 'Jl. Dago No. 100, Coblong, Bandung, Jawa Barat',
                'catatan_jamaah' => 'Ingin paket VIP',
                'nama_paspor' => 'SITI AMINAH',
                'nomor_paspor' => 'B7654321',
                'kantor_imigrasi' => 'Bandung',
                'tgl_paspor_aktif' => '2024-06-15',
                'tgl_paspor_expired' => '2029-06-15',
            ],
            [
                'kode_jamaah' => 'JM-003',
                'nik_jamaah' => '3578010101900003',
                'nama_jamaah' => 'Rahmat Hidayat',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1990-03-10',
                'kontak_jamaah' => '081567890123',
                'email_jamaah' => 'rahmat.hidayat@example.com',
                'kecamatan' => 'Gubeng',
                'kabupaten_kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'alamat_jamaah' => 'Jl. Dharmawangsa No. 25',
                'alamat_lengkap' => 'Jl. Dharmawangsa No. 25, Gubeng, Surabaya, Jawa Timur',
                'catatan_jamaah' => 'Berangkat bersama keluarga',
                'nama_paspor' => 'RAHMAT HIDAYAT',
                'nomor_paspor' => 'C9876543',
                'kantor_imigrasi' => 'Surabaya',
                'tgl_paspor_aktif' => '2025-02-01',
                'tgl_paspor_expired' => '2030-02-01',
            ],
        ];

        foreach ($jamaahs as $jamaah) {
            Jamaah::create($jamaah);
        }
    }
}
