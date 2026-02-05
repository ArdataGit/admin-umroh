<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kota;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kotas = [
            ['kode_kota' => 'BTJ', 'nama_kota' => 'Banda Aceh'],
            ['kode_kota' => 'KNO', 'nama_kota' => 'Medan'],
            ['kode_kota' => 'PDG', 'nama_kota' => 'Padang'],
            ['kode_kota' => 'PKU', 'nama_kota' => 'Pekanbaru'],
            ['kode_kota' => 'BTH', 'nama_kota' => 'Batam'],
            ['kode_kota' => 'TNJ', 'nama_kota' => 'Tanjung Pinang'],
            ['kode_kota' => 'DJB', 'nama_kota' => 'Jambi'],
            ['kode_kota' => 'PLM', 'nama_kota' => 'Palembang'],
            ['kode_kota' => 'PGK', 'nama_kota' => 'Pangkal Pinang'],
            ['kode_kota' => 'BKS', 'nama_kota' => 'Bengkulu'],
            ['kode_kota' => 'TKG', 'nama_kota' => 'Bandar Lampung'],
            ['kode_kota' => 'CGK', 'nama_kota' => 'Jakarta'],
            ['kode_kota' => 'BDO', 'nama_kota' => 'Bandung'],
            ['kode_kota' => 'CBN', 'nama_kota' => 'Cirebon'],
            ['kode_kota' => 'SRG', 'nama_kota' => 'Semarang'],
            ['kode_kota' => 'YIA', 'nama_kota' => 'Yogyakarta (YIA)'],
            ['kode_kota' => 'SOC', 'nama_kota' => 'Solo (Surakarta)'],
            ['kode_kota' => 'SUB', 'nama_kota' => 'Surabaya'],
            ['kode_kota' => 'MLG', 'nama_kota' => 'Malang'],
            ['kode_kota' => 'BWX', 'nama_kota' => 'Banyuwangi'],
            ['kode_kota' => 'DPS', 'nama_kota' => 'Denpasar/Bali'],
            ['kode_kota' => 'LOP', 'nama_kota' => 'Lombok'],
            ['kode_kota' => 'BMU', 'nama_kota' => 'Bima'],
            ['kode_kota' => 'KOE', 'nama_kota' => 'Kupang'],
            ['kode_kota' => 'LBJ', 'nama_kota' => 'Labuan Bajo'],
            ['kode_kota' => 'ENE', 'nama_kota' => 'Ende'],
            ['kode_kota' => 'MOF', 'nama_kota' => 'Maumere'],
            ['kode_kota' => 'WGP', 'nama_kota' => 'Waingapu'],
            ['kode_kota' => 'PNK', 'nama_kota' => 'Pontianak'],
            ['kode_kota' => 'PKY', 'nama_kota' => 'Palangkaraya'],
            ['kode_kota' => 'BDJ', 'nama_kota' => 'Banjarmasin'],
            ['kode_kota' => 'BPN', 'nama_kota' => 'Balikpapan'],
            ['kode_kota' => 'SRI', 'nama_kota' => 'Samarinda'],
            ['kode_kota' => 'TRK', 'nama_kota' => 'Tarakan'],
            ['kode_kota' => 'UPG', 'nama_kota' => 'Makassar'],
            ['kode_kota' => 'MDC', 'nama_kota' => 'Manado'],
            ['kode_kota' => 'GTO', 'nama_kota' => 'Gorontalo'],
            ['kode_kota' => 'PLW', 'nama_kota' => 'Palu'],
            ['kode_kota' => 'KDI', 'nama_kota' => 'Kendari'],
            ['kode_kota' => 'MJU', 'nama_kota' => 'Mamuju'],
            ['kode_kota' => 'LUW', 'nama_kota' => 'Luwuk'],
            ['kode_kota' => 'AMQ', 'nama_kota' => 'Ambon'],
            ['kode_kota' => 'TTE', 'nama_kota' => 'Ternate'],
            ['kode_kota' => 'TID', 'nama_kota' => 'Tidore'],
            ['kode_kota' => 'DJJ', 'nama_kota' => 'Jayapura'],
            ['kode_kota' => 'TIM', 'nama_kota' => 'Timika'],
            ['kode_kota' => 'MKQ', 'nama_kota' => 'Merauke'],
            ['kode_kota' => 'SOQ', 'nama_kota' => 'Sorong'],
            ['kode_kota' => 'MKW', 'nama_kota' => 'Manokwari'],
            ['kode_kota' => 'NBX', 'nama_kota' => 'Nabire'],
            ['kode_kota' => 'WMX', 'nama_kota' => 'Wamena'],
        ];

        foreach ($kotas as $kota) {
            Kota::updateOrCreate(['kode_kota' => $kota['kode_kota']], $kota);
        }
    }
}
