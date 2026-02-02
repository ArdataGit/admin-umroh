<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $fillable = [
        'kode_pelanggan',
        'nama_pelanggan',
        'kontak_pelanggan',
        'email_pelanggan',
        'kabupaten_kota',
        'jenis_kelamin',
        'status_pelanggan',
        'alamat_pelanggan',
        'catatan_pelanggan',
        'foto_pelanggan'
    ];
}
