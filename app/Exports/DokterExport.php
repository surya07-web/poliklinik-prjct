<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DokterExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'dokter')->get()->map(function ($d) {
            return [
                'Nama' => $d->name,
                'Email' => $d->email,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Email'];
    }
}