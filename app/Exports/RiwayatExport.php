<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Periksa;

class RiwayatExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = auth()->user();

        return Periksa::whereHas('daftarPoli.jadwal', function ($q) use ($user) {
            $q->where('id_dokter', $user->id);
        })->get();
    }
}
