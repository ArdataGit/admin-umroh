<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratIzinCuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_id',
        'keberangkatan_umroh_id',
        'nomor_dokumen',
        'kantor_instansi',
        'nik_instansi',
        'jabatan_instansi',
        'nama_ayah',
        'nama_kakek',
        'catatan'
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function keberangkatanUmroh()
    {
        return $this->belongsTo(KeberangkatanUmroh::class);
    }
}
