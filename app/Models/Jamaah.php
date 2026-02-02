<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    protected $appends = ['nama_lengkap'];

    protected $fillable = [
        'kode_jamaah',
        'nik_jamaah',
        'nama_jamaah',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'kontak_jamaah',
        'email_jamaah',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'alamat_jamaah',
        'alamat_lengkap',
        'catatan_jamaah',
        'nama_paspor',
        'nomor_paspor',
        'kantor_imigrasi',
        'tgl_paspor_aktif',
        'tgl_paspor_expired',
        'foto_jamaah',
        'foto_ktp',
        'foto_kk',
        'foto_paspor_1',
        'foto_paspor_2'
    ];
    public function tabunganUmrohs()
    {
        return $this->hasMany(TabunganUmroh::class, 'jamaah_id');
    }

    public function tabunganHajis()
    {
        return $this->hasMany(TabunganHaji::class, 'jamaah_id');
    }

    public function getNamaLengkapAttribute()
    {
        return $this->nama_jamaah;
    }
}
