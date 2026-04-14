<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Periksa;
use App\Exports\DokterExport;
use App\Exports\PasienExport;
use App\Exports\ObatExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahPasien = User::where('role', 'pasien')->count();
        $jumlahDokter = User::where('role', 'dokter')->count();
        $jumlahPeriksa = Periksa::count();

        return view('admin.dashboard', compact(
            'jumlahPasien',
            'jumlahDokter',
            'jumlahPeriksa'
        ));
    }

    // 🔥 EXPORT DATA DOKTER
    public function exportDokter()
    {
        return Excel::download(new DokterExport, 'data-dokter.xlsx');
    }

    // 🔥 EXPORT DATA PASIEN
    public function exportPasien()
    {
        return Excel::download(new PasienExport, 'data-pasien.xlsx');
    }

    // 🔥 EXPORT DATA OBAT
    public function exportObat()
    {
        return Excel::download(new ObatExport, 'data-obat.xlsx');
    }
}