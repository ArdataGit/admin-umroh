<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeberangkatanUmroh extends Model
{
    protected $fillable = [
        'kode_keberangkatan',
        'paket_umroh_id',
        'nama_keberangkatan',
        'tanggal_keberangkatan',
        'jumlah_hari',
        'kuota_jamaah',
        'status_keberangkatan',
        'catatan'
    ];

    public function paketUmroh()
    {
        return $this->belongsTo(PaketUmroh::class);
    }

    public function customerUmrohs()
    {
        return $this->hasMany(CustomerUmroh::class);
    }
}
