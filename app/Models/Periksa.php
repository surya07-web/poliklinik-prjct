<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    protected $table = 'periksa';

    protected $fillable = [
        'id_daftar_poli',
        'tanggal_periksa',
        'catatan',
        'biaya_periksa',
    ];

    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    public function detailPeriksa()
    {
        return $this->hasMany(\App\Models\DetailPeriksa::class, 'id_periksa');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
}