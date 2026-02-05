<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'kode_tiket',
        'jenis_tiket',
        'nama_tiket',
        'satuan_unit',
        'maskapai_id',
        'kode_maskapai',
        'rute_tiket',
        'kode_pnr',
        'jumlah_tiket',
        'tanggal_keberangkatan',
        'tanggal_kepulangan',
        'jumlah_hari',
        'harga_modal',
        'harga_jual',
        'status_tiket',
        'kode_tiket_1',
        'kode_tiket_2',
        'kode_tiket_3',
        'kode_tiket_4',
        'catatan_tiket',
        'foto_tiket'
    ];

    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'maskapai_id');
    }
}
