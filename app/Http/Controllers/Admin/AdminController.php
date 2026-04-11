<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Periksa;

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
}