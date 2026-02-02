<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeberangkatanHaji extends Model
{
    protected $fillable = [
        'kode_keberangkatan',
        'paket_haji_id',
        'nama_keberangkatan',
        'tanggal_keberangkatan',
        'jumlah_hari',
        'kuota_jamaah',
        'status_keberangkatan',
        'catatan'
    ];

    public function paketHaji()
    {
        return $this->belongsTo(PaketHaji::class);
    }

    public function customerHajis()
    {
        return $this->hasMany(CustomerHaji::class);
    }
}
