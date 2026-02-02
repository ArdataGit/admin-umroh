<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerUmroh extends Model
{
    protected $fillable = [
        'keberangkatan_umroh_id',
        'jamaah_id',
        'agent_id',
        'tipe_kamar',
        'jumlah_jamaah',
        'nama_keluarga',
        'harga_paket',
        'diskon',
        'total_tagihan',
        'total_bayar',
        'sisa_tagihan',
        'metode_pembayaran',
        'status_visa',
        'status_tiket',
        'status_siskopatuh',
        'status_perlengkapan',
        'catatan'
    ];

    public function keberangkatanUmroh()
    {
        return $this->belongsTo(KeberangkatanUmroh::class);
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
