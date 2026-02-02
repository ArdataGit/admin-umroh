<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranUmroh extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_umroh_id',
        'kode_transaksi',
        'jumlah_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'kode_referensi',
        'tanggal_pembayaran',
        'catatan'
    ];

    public function customerUmroh()
    {
        return $this->belongsTo(CustomerUmroh::class);
    }
}
