<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Maskapai;
use App\Models\Hotel;

class PaketUmrohSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have dependencies
        if (Maskapai::count() == 0) {
            $this->call(MaskapaiSeeder::class);
        }
        if (Hotel::count() == 0) {
            $this->call(HotelSeeder::class);
        }

        $maskapai = Maskapai::first();
        $hotelMekkah = Hotel::where('lokasi_hotel', 'Mekkah')->first();
        $hotelMadinah = Hotel::where('lokasi_hotel', 'Madinah')->first();

        // Fallback if no specific hotels found
        if (!$hotelMekkah) $hotelMekkah = Hotel::first();
        if (!$hotelMadinah) $hotelMadinah = Hotel::first();

        $pakets = [
            [
                'kode_paket' => 'PKT-001',
                'nama_paket' => 'Umroh Reguler 9 Hari',
                'tanggal_keberangkatan' => now()->addDays(30),
                'jumlah_hari' => 9,
                'status_paket' => 'active',
                'kuota_jamaah' => 45,
                'maskapai_id' => $maskapai ? $maskapai->id : null,
                'rute_penerbangan' => 'direct',
                'lokasi_keberangkatan' => 'Jakarta',
                
                // Paket 1 (Quad)
                'jenis_paket_1' => 'Quad',
                'hotel_mekkah_1' => $hotelMekkah ? $hotelMekkah->id : null,
                'hotel_madinah_1' => $hotelMadinah ? $hotelMadinah->id : null,
                'hotel_transit_1' => null,
                'harga_hpp_1' => 20000000,
                'harga_quad_1' => 25000000,
                'harga_triple_1' => 26000000,
                'harga_double_1' => 27000000,

                // Paket 2 (Double) - Optional
                'jenis_paket_2' => 'Double',
                'hotel_mekkah_2' => $hotelMekkah ? $hotelMekkah->id : null,
                'hotel_madinah_2' => $hotelMadinah ? $hotelMadinah->id : null,
                'hotel_transit_2' => null,
                'harga_hpp_2' => 22000000,
                'harga_quad_2' => 0,
                'harga_triple_2' => 0,
                'harga_double_2' => 29000000,

                'termasuk_paket' => 'Visa, Tiket, Hotel, bus AC',
                'tidak_termasuk_paket' => 'Paspor, Vaksin',
                'syarat_ketentuan' => 'DP 5 Juta',
                'catatan_paket' => 'Best Seller',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_paket' => 'PKT-002',
                'nama_paket' => 'Umroh Plus Turki 12 Hari',
                'tanggal_keberangkatan' => now()->addDays(60),
                'jumlah_hari' => 12,
                'status_paket' => 'active',
                'kuota_jamaah' => 30,
                'maskapai_id' => $maskapai ? $maskapai->id : null,
                'rute_penerbangan' => 'transit',
                'lokasi_keberangkatan' => 'Jakarta',
                
                'jenis_paket_1' => 'Quad',
                'hotel_mekkah_1' => $hotelMekkah ? $hotelMekkah->id : null,
                'hotel_madinah_1' => $hotelMadinah ? $hotelMadinah->id : null,
                'hotel_transit_1' => null,
                'harga_hpp_1' => 28000000,
                'harga_quad_1' => 32000000,
                'harga_triple_1' => 33500000,
                'harga_double_1' => 35000000,
                
                'jenis_paket_2' => null,
                'hotel_mekkah_2' => null,
                'hotel_madinah_2' => null,
                'hotel_transit_2' => null,
                'harga_hpp_2' => 0,
                'harga_quad_2' => 0,
                'harga_triple_2' => 0,
                'harga_double_2' => 0,

                'termasuk_paket' => 'Visa Umroh & Turki, Tiket, Hotel',
                'tidak_termasuk_paket' => 'Pengeluaran Pribadi',
                'syarat_ketentuan' => 'DP 10 Juta',
                'catatan_paket' => 'Wisata Halal Turki',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'kode_paket' => 'PKT-003',
                'nama_paket' => 'Umroh Ramadhan',
                'tanggal_keberangkatan' => now()->addDays(90),
                'jumlah_hari' => 15,
                'status_paket' => 'active',
                'kuota_jamaah' => 50,
                'maskapai_id' => $maskapai ? $maskapai->id : null,
                'rute_penerbangan' => 'direct',
                'lokasi_keberangkatan' => 'Surabaya',
                
                'jenis_paket_1' => 'Quad',
                'hotel_mekkah_1' => $hotelMekkah ? $hotelMekkah->id : null,
                'hotel_madinah_1' => $hotelMadinah ? $hotelMadinah->id : null,
                'hotel_transit_1' => null,
                'harga_hpp_1' => 30000000,
                'harga_quad_1' => 35000000,
                'harga_triple_1' => 37000000,
                'harga_double_1' => 40000000,

                'jenis_paket_2' => null,
                'hotel_mekkah_2' => null,
                'hotel_madinah_2' => null,
                'hotel_transit_2' => null,
                'harga_hpp_2' => 0,
                'harga_quad_2' => 0,
                'harga_triple_2' => 0,
                'harga_double_2' => 0,

                'termasuk_paket' => 'Full Board',
                'tidak_termasuk_paket' => 'Laundry',
                'syarat_ketentuan' => 'Pelunasan H-30',
                'catatan_paket' => 'Awal Ramadhan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('paket_umrohs')->insert($pakets);
    }
}
