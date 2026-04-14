<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Models\Obat;
use App\Models\DetailPeriksa;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\AntrianUpdated;

class DokterController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $antrians = DaftarPoli::with([
            'pasien',
            'jadwal.poli',
            'jadwal.dokter',
            'periksa.detailPeriksa.obat'
        ])
        ->whereHas('jadwal', function ($q) use ($user) {
            $q->where('id_dokter', $user->id);
        })
        ->orderBy('no_antrian')
        ->get();

        return view('dokter.dashboard', compact('antrians'));
    }

    // 🔥 PANGGIL PASIEN
    public function panggil($id)
    {
        $antrian = DaftarPoli::with([
            'jadwal.poli',
            'jadwal.dokter'
        ])->findOrFail($id);

        if ($antrian->jadwal->id_dokter != auth()->id()) {
            abort(403);
        }

        if ($antrian->status === 'dipanggil') {
            return back()->with('error', 'Pasien sudah dipanggil');
        }

        // ✅ UPDATE STATUS
        $antrian->update([
            'status' => 'dipanggil'
        ]);

        // ✅ BUAT DATA PERIKSA (AWAL)
        if (!$antrian->periksa) {
            Periksa::create([
                'id_daftar_poli' => $antrian->id,
                'tanggal_periksa' => now(),
            ]);
        }

        // 🔥 REALTIME
        broadcast(new AntrianUpdated($antrian));

        return back()->with('success', 'Pasien dipanggil');
    }

    // 🔵 FORM PERIKSA
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

    // 🔥 SIMPAN PEMERIKSAAN
    public function simpanPeriksa(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
            'biaya_periksa' => 'required|numeric|min:0',
            'obat' => 'required|array',
            'obat.*' => 'exists:obat,id',
        ]);

        DB::beginTransaction();

        try {
            $periksa = Periksa::with([
                'daftarPoli.jadwal.poli',
                'daftarPoli.jadwal.dokter'
            ])->findOrFail($id);

            if ($periksa->detailPeriksa()->exists()) {
                throw new \Exception("Pemeriksaan sudah pernah disimpan!");
            }

            $totalObat = 0;
            $selectedObats = [];

            foreach ($request->obat as $id_obat) {
                $obat = Obat::lockForUpdate()->findOrFail($id_obat);

                if ($obat->stok <= 0) {
                    throw new \Exception("Stok obat {$obat->nama_obat} habis!");
                }

                $totalObat += $obat->harga;
                $selectedObats[] = $obat;
            }

            $total = $request->biaya_periksa + $totalObat;

            // SIMPAN DETAIL OBAT
            foreach ($selectedObats as $obat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obat->id,
                ]);

                $obat->decrement('stok');
            }

            // UPDATE PERIKSA (SELESAI)
            $periksa->update([
                'catatan' => $request->catatan,
                'biaya_periksa' => $total,
            ]);

            // 🔥 UPDATE STATUS ANTRIAN JADI SELESAI
            $periksa->daftarPoli->update([
                'status' => 'selesai'
            ]);

            // PEMBAYARAN
            if (!$periksa->pembayaran) {
                Pembayaran::create([
                    'periksa_id' => $periksa->id,
                    'total_bayar' => $total,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            // 🔥 REALTIME UPDATE
            broadcast(new AntrianUpdated($periksa->daftarPoli));

            return back()
                ->with('success', 'Pemeriksaan berhasil disimpan')
                ->with('done', true); // 🔥 ini penting

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // 🔵 RIWAYAT PASIEN
    public function riwayat($id_pasien)
    {
        $user = auth()->user();

        $riwayats = DaftarPoli::with([
            'jadwal.poli',
            'periksa.detailPeriksa.obat'
        ])
        ->where('id_pasien', $id_pasien)
        ->whereHas('jadwal', function ($q) use ($user) {
            $q->where('id_dokter', $user->id);
        })
        ->latest()
        ->get();

        return view('dokter.riwayat', compact('riwayats'));
    }
}