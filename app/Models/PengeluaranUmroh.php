<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranUmroh extends Model
{
    protected $fillable = [
        'keberangkatan_umroh_id',
        'kode_pengeluaran',
        'tanggal_pengeluaran',
        'jenis_pengeluaran',
        'nama_pengeluaran',
        'jumlah_pengeluaran',
        'catatan_pengeluaran',
        'bukti_pengeluaran'
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
        'jumlah_pengeluaran' => 'decimal:2',
    ];

    public function keberangkatanUmroh()
    {
        return $this->belongsTo(KeberangkatanUmroh::class);
    }
}
