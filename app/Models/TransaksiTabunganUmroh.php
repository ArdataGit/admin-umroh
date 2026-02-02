<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiTabunganUmroh extends Model
{
    protected $fillable = [
        'tabungan_umroh_id',
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

    public function tabunganUmroh()
    {
        return $this->belongsTo(TabunganUmroh::class);
    }
}
