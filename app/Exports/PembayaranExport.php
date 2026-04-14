<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;

class PembayaranExport implements FromCollection
{
    public function collection()
    {
        return Pembayaran::all();
    }
}