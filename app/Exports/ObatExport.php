<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ObatExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Obat::all()->map(function ($o) {
            return [
                'Nama Obat' => $o->nama_obat,
                'Stok' => $o->stok,
                'Harga' => $o->harga,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Obat', 'Stok', 'Harga'];
    }
}