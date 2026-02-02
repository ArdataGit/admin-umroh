<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_adjustment',
        'tanggal_adjustment',
        'produk_id',
        'tipe_adjustment',
        'stok_awal',
        'koreksi_stock',
        'stok_akhir',
        'user_id',
        'status_approval',
        'catatan'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
