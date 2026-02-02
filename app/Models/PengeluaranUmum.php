<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranUmum extends Model
{
    use HasFactory;

    protected $fillable = [
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
}
