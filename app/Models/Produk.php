<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'standar_stok',
        'aktual_stok',
        'satuan_unit',
        'harga_beli',
        'harga_jual',
        'kurs',
        'harga_beli_asing',
        'harga_jual_asing',
        'catatan_produk',
        'foto_produk'
    ];

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class, 'produk_id');
    }
}
