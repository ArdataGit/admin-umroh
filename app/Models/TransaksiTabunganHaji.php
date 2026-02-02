<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiTabunganHaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'tabungan_haji_id',
        'kode_transaksi',
        'tanggal_transaksi',
        'jenis_transaksi',
        'nominal',
        'metode_pembayaran',
        'status_setoran',
        'kode_referensi',
        'keterangan',
        'bukti_transaksi'
    ];

    public function tabunganHaji()
    {
        return $this->belongsTo(TabunganHaji::class, 'tabungan_haji_id');
    }
}
