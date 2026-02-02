<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranProdukDetail extends Model
{
    protected $fillable = [
        'pengeluaran_produk_id',
        'produk_id',
        'quantity',
        'harga_satuan',
        'total_harga'
    ];

    public function pengeluaran()
    {
        return $this->belongsTo(PengeluaranProduk::class, 'pengeluaran_produk_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
