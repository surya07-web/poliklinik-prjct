<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPeriksa extends Model
{
    protected $table = 'jadwal_periksa';

    protected $fillable = [
        'id_poli',     
        'id_dokter',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    // RELASI KE POLI
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    // RELASI KE DOKTER
    public function dokter()
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }

    // RELASI KE PENDAFTARAN
    public function daftarPoli()
    {
        return $this->hasMany(DaftarPoli::class, 'id_jadwal');
    }
}