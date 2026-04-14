<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'periksa_id',
        'total_bayar',
        'bukti_pembayaran',
        'status',
    ];

    public function periksa()
    {
        return $this->belongsTo(Periksa::class);
    }
}
