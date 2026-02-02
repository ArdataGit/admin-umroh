<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'catatan'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
