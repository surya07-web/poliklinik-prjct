<?php

namespace App\Exports;

use App\Models\Periksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RiwayatDokterExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Periksa::whereHas('daftarPoli.jadwal', function ($q) {
            $q->where('id_dokter', auth()->id());
        })
        ->whereNotNull('catatan')
        ->get()
        ->map(function ($p) {
            return [
                'Nama Pasien' => $p->pasien->name ?? '-',
                'Tanggal Periksa' => $p->created_at->format('Y-m-d'),
                'Catatan' => $p->catatan,
                'Biaya' => $p->biaya_periksa,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pasien',
            'Tanggal Periksa',
            'Catatan',
            'Biaya',
        ];
    }
}