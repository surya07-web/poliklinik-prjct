<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    // 🔹 tampilkan tagihan pasien
    public function index()
    {
        $user = auth()->user();

        $pembayarans = Pembayaran::with('periksa.daftarPoli.jadwal.poli')
            ->whereHas('periksa.daftarPoli', function ($q) use ($user) {
                $q->where('id_pasien', $user->id);
            })
            ->latest()
            ->get();

        return view('pasien.pembayaran', compact('pembayarans'));
    }

    // 🔹 upload bukti pembayaran
    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        // simpan file
        $path = $request->file('bukti')->store('bukti', 'public');

        $pembayaran->update([
            'bukti_pembayaran' => $path,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload');
    }
}