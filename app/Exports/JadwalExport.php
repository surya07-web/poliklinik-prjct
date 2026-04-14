<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\JadwalPeriksa;

class JadwalExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = auth()->user();

        return JadwalPeriksa::where('id_dokter', $user->id)->get();
    }
}
