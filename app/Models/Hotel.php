<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'kode_hotel',
        'nama_hotel',
        'lokasi_hotel',
        'kontak_hotel',
        'email_hotel',
        'rating_hotel',
        'kurs',
        'kurs_asing',
        'harga_hotel',
        'catatan_hotel'
    ];

    public function paketUmrohsMekkah1()
    {
        return $this->hasMany(PaketUmroh::class, 'hotel_mekkah_1');
    }

    public function paketUmrohsMadinah1()
    {
        return $this->hasMany(PaketUmroh::class, 'hotel_madinah_1');
    }

    public function paketUmrohsTransit1()
    {
        return $this->hasMany(PaketUmroh::class, 'hotel_transit_1');
    }

    public function paketUmrohsMekkah2()
    {
        return $this->hasMany(PaketUmroh::class, 'hotel_mekkah_2');
    }

    public function paketUmrohsMadinah2()
    {
        return $this->hasMany(PaketUmroh::class, 'hotel_madinah_2');
    }

    public function paketUmrohsTransit2()
    {
        return $this->hasMany(PaketUmroh::class, 'hotel_transit_2');
    }

    // Paket Haji Relations
    public function paketHajisMekkah1()
    {
        return $this->hasMany(PaketHaji::class, 'hotel_mekkah_1');
    }

    public function paketHajisMadinah1()
    {
        return $this->hasMany(PaketHaji::class, 'hotel_madinah_1');
    }

    public function paketHajisTransit1()
    {
        return $this->hasMany(PaketHaji::class, 'hotel_transit_1');
    }

    public function paketHajisMekkah2()
    {
        return $this->hasMany(PaketHaji::class, 'hotel_mekkah_2');
    }

    public function paketHajisMadinah2()
    {
        return $this->hasMany(PaketHaji::class, 'hotel_madinah_2');
    }

    public function paketHajisTransit2()
    {
        return $this->hasMany(PaketHaji::class, 'hotel_transit_2');
    }
}
