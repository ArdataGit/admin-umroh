<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketHaji extends Model
{
    protected $fillable = [
        'kode_paket',
        'nama_paket',
        'tanggal_keberangkatan',
        'jumlah_hari',
        'status_paket',
        'kuota_jamaah',
        'maskapai_id',
        'rute_penerbangan',
        'lokasi_keberangkatan',
        'jenis_paket_1',
        'hotel_mekkah_1',
        'hari_mekkah_1',
        'hotel_madinah_1',
        'hari_madinah_1',
        'hotel_transit_1',
        'hari_transit_1',
        'harga_hpp_1',
        'is_include_makan_1',
        'hpp_quad1',
        'hpp_triple1',
        'hpp_double1',
        'harga_quad_1',
        'harga_triple_1',
        'harga_double_1',
        'jenis_paket_2',
        'hotel_mekkah_2',
        'hari_mekkah_2',
        'hotel_madinah_2',
        'hari_madinah_2',
        'hotel_transit_2',
        'hari_transit_2',
        'harga_hpp_2',
        'is_include_makan_2',
        'hpp_quad2',
        'hpp_triple2',
        'hpp_double2',
        'harga_quad_2',
        'harga_triple_2',
        'harga_double_2',
        'termasuk_paket',
        'tidak_termasuk_paket',
        'syarat_ketentuan',
        'catatan_paket',
        'foto_brosur'
    ];

    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'maskapai_id');
    }

    public function hotelMekkah1()
    {
        return $this->belongsTo(Hotel::class, 'hotel_mekkah_1');
    }

    public function hotelMadinah1()
    {
        return $this->belongsTo(Hotel::class, 'hotel_madinah_1');
    }

    public function hotelTransit1()
    {
        return $this->belongsTo(Hotel::class, 'hotel_transit_1');
    }

    public function hotelMekkah2()
    {
        return $this->belongsTo(Hotel::class, 'hotel_mekkah_2');
    }

    public function hotelMadinah2()
    {
        return $this->belongsTo(Hotel::class, 'hotel_madinah_2');
    }

    public function hotelTransit2()
    {
        return $this->belongsTo(Hotel::class, 'hotel_transit_2');
    }

    public function layanans()
    {
        return $this->belongsToMany(Layanan::class, 'paket_haji_layanan', 'paket_haji_id', 'layanan_id');
    }
}
