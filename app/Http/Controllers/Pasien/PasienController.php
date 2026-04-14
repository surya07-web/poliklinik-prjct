<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;

class PasienController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // 🔴 ANTRIAN AKTIF (BELUM DIPERIKSA & BELUM SELESAI)
        $antrianAktif = DaftarPoli::with('jadwal.poli', 'jadwal.dokter')
            ->where('id_pasien', $user->id)
            ->whereHas('periksa', function ($q) {
                $q->whereNull('catatan'); // 🔥 MASIH DIPROSES
            })
            ->orWhereDoesntHave('periksa') // 🔥 BELUM DIPANGGIL
            ->latest()
            ->first();

        // 🔥 ANTRIAN TERAKHIR DIPANGGIL (GLOBAL)
        $antrianSekarang = DaftarPoli::with('jadwal.poli', 'jadwal.dokter')
            ->whereHas('periksa')
            ->latest('updated_at')
            ->first();

        // 🟢 SEMUA JADWAL
        $jadwals = JadwalPeriksa::with('poli', 'dokter')->get();

        // 🟡 RIWAYAT PASIEN
        $riwayats = DaftarPoli::with([
            'jadwal.poli',
            'periksa.detailPeriksa.obat'
        ])
        ->where('id_pasien', $user->id)
        ->latest()
        ->get();

        // 🔥 STATISTIK
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

    // 🔥 DAFTAR POLI
    public function daftar(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id_jadwal' => 'required',
            'keluhan' => 'required'
        ]);

        // 🔒 CEK ANTRIAN AKTIF (BELUM SELESAI)
        $cek = DaftarPoli::where('id_pasien', $user->id)
            ->where(function ($q) {
                $q->whereDoesntHave('periksa')
                  ->orWhereHas('periksa', function ($q2) {
                      $q2->whereNull('catatan'); // 🔥 MASIH DIPROSES
                  });
            })
            ->exists();

        if ($cek) {
            return back()->with('error', 'Anda masih memiliki antrian aktif!');
        }

        // 🔢 NOMOR ANTRIAN
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

    // 🔵 RIWAYAT
    public function riwayat()
    {
        $user = auth()->user();

        $riwayats = DaftarPoli::with([
            'jadwal.poli',
            'jadwal.dokter',
            'periksa.detailPeriksa.obat'
        ])
        ->where('id_pasien', $user->id)
        ->latest()
        ->get();

        return view('pasien.riwayat', compact('riwayats'));
    }

    // 🔍 DETAIL PEMERIKSAAN
    public function detail($id)
    {
        $periksa = Periksa::with([
            'daftarPoli.jadwal.poli',
            'daftarPoli.jadwal.dokter',
            'detailPeriksa.obat'
        ])->findOrFail($id);

        return view('pasien.detail', compact('periksa'));
    }

    // 🔥 API LIVE ANTRIAN (OPTIONAL - kalau pakai AJAX)
    public function antrianLive()
    {
        $sekarang = DaftarPoli::whereHas('periksa')
            ->orderBy('updated_at', 'desc')
            ->with('jadwal.poli', 'jadwal.dokter')
            ->first();

        $jadwals = JadwalPeriksa::with(['daftarPoli' => function($q){
            $q->whereHas('periksa')
              ->orderBy('updated_at', 'desc');
        }])->get();

        $perJadwal = $jadwals->map(function($j){
            return [
                'id' => $j->id,
                'nomor' => optional($j->daftarPoli->first())->no_antrian ?? '-'
            ];
        });

        return response()->json([
            'no_antrian' => optional($sekarang)->no_antrian ?? '-',
            'poli' => optional($sekarang->jadwal?->poli)->nama ?? '-',
            'dokter' => optional($sekarang->jadwal?->dokter)->name ?? '-',
            'per_jadwal' => $perJadwal
        ]);
    }
}