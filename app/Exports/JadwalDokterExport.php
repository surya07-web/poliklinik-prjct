<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JadwalDokterExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return JadwalPeriksa::where('id_dokter', auth()->id())
            ->get()
            ->map(function ($j) {
                return [
                    'Hari' => $j->hari,
                    'Jam Mulai' => $j->jam_mulai,
                    'Jam Selesai' => $j->jam_selesai,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Hari',
            'Jam Mulai',
            'Jam Selesai',
        ];
    }
}