<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiTiket extends Model
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
        'catatan',
        'bukti_transaksi'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiTiketDetail::class);
    }

    public function pembayaranTikets()
    {
        return $this->hasMany(PembayaranTiket::class);
    }
}
