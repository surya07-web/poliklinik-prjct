<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Models\Obat;
use App\Models\DetailPeriksa;

class DokterController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $antrians = DaftarPoli::with([
            'pasien',
            'jadwal.poli',
            'periksa.detailPeriksa.obat' // 🔥 penting untuk tampil obat
        ])
        ->whereHas('jadwal', function ($q) use ($user) {
            $q->where('id_dokter', $user->id);
        })
        ->orderBy('no_antrian')
        ->get();

        return view('dokter.dashboard', compact('antrians'));
    }

    public function panggil($id)
    {
        $antrian = DaftarPoli::with('jadwal')->findOrFail($id);

        // 🔒 Cegah akses dokter lain
        if ($antrian->jadwal->id_dokter != auth()->id()) {
            abort(403);
        }

        // ❗ Cegah double panggil
        if ($antrian->periksa) {
            return redirect()->back()->with('error', 'Pasien sudah dipanggil');
        }

        Periksa::create([
            'id_daftar_poli' => $antrian->id,
            'tanggal_periksa' => now(), // ✅ WAJIB sesuai DB
        ]);

        $antrian->touch(); // 🔥 update timestamp untuk antrian

        return redirect()->back()->with('success', 'Pasien dipanggil');
    }

    public function formPeriksa($id)
    {
        $periksa = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwal.poli',
            'detailPeriksa.obat'
        ])->findOrFail($id);

        $obats = Obat::all();

        return view('dokter.periksa', compact('periksa', 'obats'));
    }

    public function simpanPeriksa(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
            'biaya_periksa' => 'required|numeric|min:0',
            'obat' => 'required|array',
            'obat.*' => 'exists:obat,id',
        ]);

        $periksa = Periksa::findOrFail($id);

        $totalObat = 0;
        $selectedObats = [];

        // 🔥 ambil semua data obat sekali
        foreach ($request->obat as $id_obat) {
            $obat = Obat::findOrFail($id_obat);

            // ❗ validasi stok
            if ($obat->stok <= 0) {
                return back()->with('error', 'Stok obat ' . $obat->nama_obat . ' habis!');
            }

            $totalObat += $obat->harga;
            $selectedObats[] = $obat;
        }

        // 🔥 hitung total
        $total = $request->biaya_periksa + $totalObat;

        // 🔥 hapus detail lama
        DetailPeriksa::where('id_periksa', $periksa->id)->delete();

        // 🔥 simpan + kurangi stok
        foreach ($selectedObats as $obat) {
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat' => $obat->id,
            ]);

            $obat->decrement('stok');
        }

        // 🔥 update periksa
        $periksa->update([
            'catatan' => $request->catatan,
            'biaya_periksa' => $total,
        ]);

        return redirect()->route('dokter.dashboard')
            ->with('success', 'Pemeriksaan berhasil disimpan');
    }

    public function riwayat($id_pasien)
    {
        $riwayats = DaftarPoli::with([
            'jadwal.poli',
            'periksa.detailPeriksa.obat'
        ])
        ->where('id_pasien', $id_pasien)
        ->latest()
        ->get();

        return view('dokter.riwayat', compact('riwayats'));
    }

    public function struk($id)
    {
        $periksa = Periksa::with(
            'daftarPoli.pasien',
            'daftarPoli.jadwal.poli',
            'detailPeriksa.obat'
        )->findOrFail($id);

        return view('dokter.struk', compact('periksa'));
    }
}