<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranTiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_tiket_id',
        'kode_transaksi',
        'tanggal_pembayaran',
        'jumlah_pembayaran',
        'kurs',
        'kurs_asing',
        'metode_pembayaran',
        'status_pembayaran',
        'kode_referensi',
        'catatan',
        'bukti_pembayaran'
    ];

    public function transaksiTiket()
    {
        return $this->belongsTo(TransaksiTiket::class);
    }
}
