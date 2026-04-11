<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;

class AntrianDisplayController extends Controller
{
    public function index()
    {
        // ambil antrian terakhir yang dipanggil
        $antrian = DaftarPoli::with('jadwal.poli', 'jadwal.dokter')
            ->whereHas('periksa')
            ->latest()
            ->first();

        return view('display.antrian', compact('antrian'));
    }

    public function data()
    {
        $antrian = DaftarPoli::with('jadwal.poli', 'jadwal.dokter')
            ->whereHas('periksa')
            ->latest()
            ->first();

        return response()->json($antrian);
    }
}