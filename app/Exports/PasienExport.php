<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PasienExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'pasien')->get()->map(function ($p) {
            return [
                'Nama' => $p->name,
                'Email' => $p->email,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Email'];
    }
}