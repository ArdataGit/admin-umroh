<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketHajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maskapai = \App\Models\Maskapai::first();
        $hotel = \App\Models\Hotel::first();

        // Create if relationships exist, otherwise skip to avoid errors
        if ($maskapai && $hotel) {
            \App\Models\PaketHaji::create([
                'kode_paket' => 'PH-2026-001',
                'nama_paket' => 'Haji Plus Kuota Resmi 2026',
                'tanggal_keberangkatan' => '2026-06-15',
                'jumlah_hari' => 25,
                'status_paket' => 'active',
                'kuota_jamaah' => 45,
                'maskapai_id' => $maskapai->id,
                'rute_penerbangan' => 'direct',
                'lokasi_keberangkatan' => 'Jakarta',
                
                // Paket 1 (Quad/Triple/Double)
                'jenis_paket_1' => 'Maktab Vip',
                'hotel_mekkah_1' => $hotel->id,
                'hotel_madinah_1' => $hotel->id,
                'hotel_transit_1' => $hotel->id,
                'harga_hpp_1' => 180000000,
                'harga_quad_1' => 205000000,
                'harga_triple_1' => 215000000,
                'harga_double_1' => 225000000,

                // Paket 2 (Optional)
                'jenis_paket_2' => 'Maktab A',
                'hotel_mekkah_2' => $hotel->id,
                'hotel_madinah_2' => $hotel->id,
                'hotel_transit_2' => $hotel->id,
                'harga_hpp_2' => 170000000,
                'harga_quad_2' => 195000000,
                'harga_triple_2' => 205000000,
                'harga_double_2' => 215000000,

                'termasuk_paket' => "Tiket Pesawat PP\nVisa Haji\nAkomodasi Hotel\nMakan 3x Sehari\nTransportasi Bus AC\nMuthawif Berpengalaman\nAir Zamzam 5L",
                'tidak_termasuk_paket' => "Pembuatan Paspor\nVaksin Meningitis\nKelebihan Bagasi\nPengeluaran Pribadi",
                'syarat_ketentuan' => "Melunasi pembayaran H-45\nPaspor berlaku min 7 bulan\nPas foto latar putih",
                'catatan_paket' => "Harga dapat berubah sewaktu-waktu mengikuti kebijakan Arab Saudi",
                'foto_brosur' => null
            ]);

            \App\Models\PaketHaji::create([
                'kode_paket' => 'PH-2026-FUR-01',
                'nama_paket' => 'Haji Furoda Langsung Berangkat',
                'tanggal_keberangkatan' => '2026-06-20',
                'jumlah_hari' => 22,
                'status_paket' => 'active',
                'kuota_jamaah' => 20,
                'maskapai_id' => $maskapai->id,
                'rute_penerbangan' => 'transit',
                'lokasi_keberangkatan' => 'Jakarta',
                
                'jenis_paket_1' => 'VVIP',
                'hotel_mekkah_1' => $hotel->id,
                'hotel_madinah_1' => $hotel->id,
                'hotel_transit_1' => $hotel->id,
                'harga_hpp_1' => 300000000,
                'harga_quad_1' => 350000000,
                'harga_triple_1' => 365000000,
                'harga_double_1' => 380000000,

                'termasuk_paket' => "Visa Furoda\nTiket Business Class\nHotel Bintang 5 Depan Masjid\nFull Board Meals",
                'tidak_termasuk_paket' => "Dam/Hadyu\nPengeluaran Pribadi",
                'syarat_ketentuan' => "pembayaran Full Payment saat pendaftaran",
                'catatan_paket' => "Program khusus tanpa antri",
                'foto_brosur' => null
            ]);
        }
    }
}
