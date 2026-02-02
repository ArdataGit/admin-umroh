<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanUmum extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pemasukan',
        'tanggal_pemasukan',
        'jenis_pemasukan',
        'nama_pemasukan',
        'jumlah_pemasukan',
        'catatan_pemasukan',
        'bukti_pemasukan'
    ];

    protected $casts = [
        'tanggal_pemasukan' => 'date',
        'jumlah_pemasukan' => 'decimal:2',
    ];
}
