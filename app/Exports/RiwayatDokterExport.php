<?php

namespace App\Exports;

use App\Models\Periksa;
use Maatwebsite\Excel\Concerns\FromCollection;

class RiwayatDokterExport implements FromCollection
{
    public function collection()
    {
        return Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwal.poli'
        ])
        ->whereHas('daftarPoli.jadwal', function ($q) {
            $q->where('id_dokter', auth()->id());
        })
        ->latest()
        ->get()
        ->map(function ($item) {

            return [

                'Nama Pasien' =>
                    $item->daftarPoli->pasien->name ?? '-',

                'Poli' =>
                    $item->daftarPoli->jadwal->poli->nama_poli ?? '-',

                'Tanggal Periksa' =>
                    $item->tanggal_periksa ?? '-',

                'Catatan' =>
                    $item->catatan ?? '-',

                'Biaya' =>
                    $item->biaya_periksa ?? 0,
            ];
        });
    }
}