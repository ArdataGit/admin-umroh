<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianProdukDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_produk_id',
        'produk_id',
        'quantity',
        'harga_satuan',
        'total_harga'
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianProduk::class, 'pembelian_produk_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
