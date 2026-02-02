<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'kode_karyawan',
        'nik_karyawan',
        'nama_karyawan',
        'kontak_karyawan',
        'email_karyawan',
        'kabupaten_kota',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_karyawan',
        'catatan_karyawan',
        'gaji',
        'foto_karyawan'
    ];
}
