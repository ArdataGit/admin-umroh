<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabunganUmroh extends Model
{
    protected $fillable = [
        'kode_tabungan',
        'jamaah_id',
        'tanggal_pendaftaran',
        'bank_tabungan',
        'rekening_tabungan',
        'status_tabungan',
        'setoran_tabungan',
        'metode_pembayaran',
        'catatan_pembayaran'
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah_id');
    }

    public function transaksiTabunganUmrohs()
    {
        return $this->hasMany(TransaksiTabunganUmroh::class);
    }
}
