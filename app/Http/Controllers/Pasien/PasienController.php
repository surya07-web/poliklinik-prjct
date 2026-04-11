<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;

class PasienController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // ANTRIAN AKTIF (BELUM DIPERIKSA)
        $antrianAktif = DaftarPoli::with('jadwal.poli', 'jadwal.dokter')
            ->where('id_pasien', $user->id)
            ->whereDoesntHave('periksa')
            ->first();

        // ANTRIAN YANG SEDANG DIPANGGIL (GLOBAL)
        $antrianSekarang = DaftarPoli::with('jadwal.poli', 'jadwal.dokter')
            ->whereHas('periksa')
            ->latest()
            ->first();

        // SEMUA JADWAL POLI
        $jadwals = JadwalPeriksa::with('poli', 'dokter')->get();

        // RIWAYAT PASIEN
        $riwayats = DaftarPoli::with([
            'jadwal.poli',
            'periksa.detailPeriksa.obat'
        ])
        ->where('id_pasien', $user->id)
        ->latest()
        ->get();

        // STATISTIK
        $totalKunjungan = $riwayats->count();

        $totalSelesai = $riwayats->filter(function ($r) {
            return $r->periksa && $r->periksa->catatan;
        })->count();

        $totalBiaya = $riwayats->sum(function ($r) {
            return $r->periksa->biaya_periksa ?? 0;
        });

        return view('pasien.dashboard', compact(
            'antrianAktif',
            'antrianSekarang',
            'jadwals',
            'riwayats',
            'totalKunjungan',
            'totalSelesai',
            'totalBiaya'
        ));
    }

    // METHOD PENTING (DAFTAR POLI)
    public function daftar(Request $request)
        {
            $user = auth()->user();

            $request->validate([
                'id_jadwal' => 'required',
                'keluhan' => 'required'
            ]);

            $last = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();

            $no_antrian = $last + 1;

            DaftarPoli::create([
                'id_pasien' => $user->id,
                'id_jadwal' => $request->id_jadwal,
                'no_antrian' => $no_antrian,
                'keluhan' => $request->keluhan,
            ]);

            return back()->with('success', 'Nomor antrian: ' . $no_antrian);
        }

    public function riwayat()
    {
        $user = auth()->user();

        $riwayat = \App\Models\Periksa::with(
            'daftarPoli.pasien',
            'daftarPoli.jadwal.poli',
            'detailPeriksa.obat'
        )
        ->whereHas('daftarPoli', function ($q) use ($user) {
            $q->where('id_pasien', $user->id);
        })
        ->latest()
        ->get();

        return view('pasien.riwayat', compact('riwayat'));
    }

    public function antrianLive()
    {
        // antrian global
        $sekarang = DaftarPoli::whereHas('periksa')
            ->latest()
            ->first();

        // per jadwal
        $jadwals = JadwalPeriksa::with(['daftarPoli' => function($q){
            $q->whereHas('periksa')->latest();
        }])->get();

        $perJadwal = $jadwals->map(function($j){
            return [
                'id' => $j->id,
                'nomor' => optional($j->daftarPoli->first())->no_antrian ?? '-'
            ];
        });

        return response()->json([
            'nomor' => optional($sekarang)->no_antrian ?? '-',
            'per_jadwal' => $perJadwal
        ]);
    }
}