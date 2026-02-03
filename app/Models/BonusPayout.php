<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'kode_transaksi',
        'jumlah_bayar',
        'metode_pembayaran',
        'kode_referensi_mutasi',
        'tanggal_bayar',
        'bukti_pembayaran',
        'catatan',
        'status_pembayaran'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
