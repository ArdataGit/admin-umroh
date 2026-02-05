<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maskapai extends Model
{
    protected $fillable = [
        'kode_maskapai',
        'nama_maskapai',
        'rute_penerbangan',
        'lama_perjalanan',
        'harga_tiket',
        'catatan_penerbangan',
        'foto_maskapai'
    ];

    public function paketUmrohs()
    {
        return $this->hasMany(PaketUmroh::class, 'maskapai_id');
    }

    public function paketHajis()
    {
        return $this->hasMany(PaketHaji::class, 'maskapai_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'maskapai_id');
    }
}
