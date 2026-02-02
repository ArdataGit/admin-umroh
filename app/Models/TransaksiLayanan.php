<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiLayanan extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'pelanggan_id',
        'tanggal_transaksi',
        'tax_percentage',
        'discount_percentage',
        'shipping_cost',
        'total_transaksi',
        'status_transaksi',
        'alamat_transaksi',
        'catatan'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiLayananDetail::class);
    }

    public function pembayaranLayanans()
    {
        return $this->hasMany(PembayaranLayanan::class);
    }
}
