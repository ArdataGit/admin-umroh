<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiTiketDetail extends Model
{
    protected $fillable = [
        'transaksi_tiket_id',
        'ticket_id',
        'quantity',
        'harga_satuan',
        'total_harga'
    ];

    public function transaksiTiket()
    {
        return $this->belongsTo(TransaksiTiket::class, 'transaksi_tiket_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
