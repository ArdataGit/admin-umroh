<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'kode_agent',
        'nik_agent',
        'nama_agent',
        'kontak_agent',
        'email_agent',
        'kabupaten_kota',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'status_agent',
        'komisi_paket_umroh',
        'komisi_paket_haji',
        'alamat_agent',
        'catatan_agent',
        'foto_agent'
    ];
    public function bonusPayouts()
    {
        return $this->hasMany(BonusPayout::class);
    }
}
