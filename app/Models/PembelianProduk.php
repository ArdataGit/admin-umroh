<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pembelian',
        'supplier_id',
        'tanggal_pembelian',
        'tax_percentage',
        'discount_percentage',
        'shipping_cost',
        'total_pembayaran',
        'jumlah_bayar',
        'status_pembayaran',
        'metode_pembayaran',
        'catatan'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function details()
    {
        return $this->hasMany(PembelianProdukDetail::class, 'pembelian_produk_id');
    }
}
