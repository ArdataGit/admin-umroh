<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_layanan_id',
        'kode_transaksi',
        'tanggal_pembayaran',
        'jumlah_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'kode_referensi',
        'catatan'
    ];

    public function transaksiLayanan()
    {
        return $this->belongsTo(TransaksiLayanan::class);
    }
}
