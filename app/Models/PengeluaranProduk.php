<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranProduk extends Model
{
    protected $fillable = [
        'kode_pengeluaran',
        'jamaah_id',
        'tanggal_pengeluaran',
        'tax_percentage',
        'discount_percentage',
        'shipping_cost',
        'total_nominal',
        'status_pengeluaran',
        'metode_pengiriman',
        'alamat_pengiriman',
        'catatan'
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function details()
    {
        return $this->hasMany(PengeluaranProdukDetail::class);
    }
}
