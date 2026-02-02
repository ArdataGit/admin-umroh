<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $fillable = [
        'kode_layanan',
        'jenis_layanan',
        'nama_layanan',
        'satuan_unit',
        'harga_modal',
        'harga_jual',
        'status_layanan',
        'catatan_layanan',
        'foto_layanan'
    ];
}
