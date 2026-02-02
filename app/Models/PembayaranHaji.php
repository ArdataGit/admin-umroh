<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranHaji extends Model
{
    protected $fillable = [
        'customer_haji_id',
        'kode_transaksi',
        'jumlah_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'kode_referensi',
        'tanggal_pembayaran',
        'catatan'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'jumlah_pembayaran' => 'decimal:2',
    ];

    public function customerHaji()
    {
        return $this->belongsTo(CustomerHaji::class);
    }
}
