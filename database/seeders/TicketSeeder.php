<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\Maskapai;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ensure Maskapai Data Exists
        $maskapaiGA = Maskapai::firstOrCreate(
            ['kode_maskapai' => 'GA'],
            [
                'nama_maskapai' => 'Garuda Indonesia',
                'rute_penerbangan' => 'CGK-JED',
                'lama_perjalanan' => '9 Jam',
                'harga_tiket' => 12000000,
                'catatan_penerbangan' => 'Full Service'
            ]
        );

        $maskapaiSV = Maskapai::firstOrCreate(
            ['kode_maskapai' => 'SV'],
            [
                'nama_maskapai' => 'Saudia Airlines',
                'rute_penerbangan' => 'CGK-JED',
                'lama_perjalanan' => '9 Jam',
                'harga_tiket' => 11500000,
                'catatan_penerbangan' => 'Direct Flight'
            ]
        );

        // 2. Create Dummy Tickets
        $tickets = [
            [
                'kode_tiket' => 'TKT-UMR-001',
                'jenis_tiket' => 'Ekonomi',
                'nama_tiket' => 'Tiket Ekonomi Garuda - Batch 1',
                'satuan_unit' => 'Pax',
                'maskapai_id' => $maskapaiGA->id,
                'kode_maskapai' => $maskapaiGA->kode_maskapai,
                'rute_tiket' => 'CGK-JED-CGK',
                'kode_pnr' => 'PNR-GA-001',
                'jumlah_tiket' => 45,
                'tanggal_keberangkatan' => Carbon::now()->addDays(30),
                'tanggal_kepulangan' => Carbon::now()->addDays(40),
                'jumlah_hari' => 10,
                'harga_modal' => 12500000,
                'harga_jual' => 14000000,
                'status_tiket' => 'active',
                'kode_tiket_1' => 'T1-01',
                'kode_tiket_2' => 'T1-02',
                'kode_tiket_3' => null,
                'kode_tiket_4' => null,
                'catatan_tiket' => 'Batch awal musim',
            ],
            [
                'kode_tiket' => 'TKT-UMR-002',
                'jenis_tiket' => 'Bisnis',
                'nama_tiket' => 'Tiket Saudia - Direct Madinah',
                'satuan_unit' => 'Pax',
                'maskapai_id' => $maskapaiSV->id,
                'kode_maskapai' => $maskapaiSV->kode_maskapai,
                'rute_tiket' => 'CGK-MED-JED-CGK',
                'kode_pnr' => 'PNR-SV-002',
                'jumlah_tiket' => 30,
                'tanggal_keberangkatan' => Carbon::now()->addDays(45),
                'tanggal_kepulangan' => Carbon::now()->addDays(55),
                'jumlah_hari' => 10,
                'harga_modal' => 25000000,
                'harga_jual' => 28000000,
                'status_tiket' => 'active',
                'kode_tiket_1' => 'T2-01',
                'kode_tiket_2' => 'T2-02',
                'kode_tiket_3' => null,
                'kode_tiket_4' => null,
                'catatan_tiket' => 'Landing Madinah',
            ],
            [
                'kode_tiket' => 'TKT-VIS-003',
                'jenis_tiket' => 'Ekonomi',
                'nama_tiket' => 'Tiket Visa Umroh',
                'satuan_unit' => 'Pax',
                'maskapai_id' => $maskapaiGA->id, 
                'kode_maskapai' => 'N/A',
                'rute_tiket' => 'N/A',
                'kode_pnr' => 'N/A',
                'jumlah_tiket' => 100,
                'tanggal_keberangkatan' => Carbon::now()->addDays(10),
                'tanggal_kepulangan' => Carbon::now()->addDays(20),
                'jumlah_hari' => 30, // Validity
                'harga_modal' => 2000000,
                'harga_jual' => 2500000,
                'status_tiket' => 'active',
                'kode_tiket_1' => null,
                'kode_tiket_2' => null,
                'kode_tiket_3' => null,
                'kode_tiket_4' => null,
                'catatan_tiket' => 'Hanya Visa',
            ],
        ];

        foreach ($tickets as $t) {
            Ticket::create($t);
        }
    }
}
