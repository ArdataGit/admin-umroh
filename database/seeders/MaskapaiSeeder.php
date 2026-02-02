<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaskapaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maskapais = [
            [
                'kode_maskapai' => 'EK',
                'nama_maskapai' => 'Emirates',
                'rute_penerbangan' => 'CGK-DXB-JED',
                'lama_perjalanan' => 12,
                'harga_tiket' => 12000000,
                'catatan_penerbangan' => 'Full Service',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_maskapai' => 'SV',
                'nama_maskapai' => 'Saudia Airlines',
                'rute_penerbangan' => 'CGK-JED',
                'lama_perjalanan' => 9,
                'harga_tiket' => 13500000,
                'catatan_penerbangan' => 'Direct Flight',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_maskapai' => 'GA',
                'nama_maskapai' => 'Garuda Indonesia',
                'rute_penerbangan' => 'CGK-JED',
                'lama_perjalanan' => 9,
                'harga_tiket' => 14000000,
                'catatan_penerbangan' => 'Direct Flight',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_maskapai' => 'QR',
                'nama_maskapai' => 'Qatar Airways',
                'rute_penerbangan' => 'CGK-DOH-JED',
                'lama_perjalanan' => 13,
                'harga_tiket' => 11500000,
                'catatan_penerbangan' => 'Transit Doha',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('maskapais')->insert($maskapais);
    }
}
