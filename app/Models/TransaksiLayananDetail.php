<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiLayananDetail extends Model
{
    protected $fillable = [
        'transaksi_layanan_id',
        'layanan_id',
        'quantity',
        'harga_satuan',
        'total_harga'
    ];

    public function transaksiLayanan()
    {
        return $this->belongsTo(TransaksiLayanan::class, 'transaksi_layanan_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
