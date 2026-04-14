<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    // 🔹 tampilkan semua pembayaran
    public function index()
    {
        $pembayarans = Pembayaran::with('periksa.daftarPoli.pasien')
            ->latest()
            ->get();

        return view('admin.pembayaran', compact('pembayarans'));
    }

    // 🔹 konfirmasi pembayaran
    public function konfirmasi($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status' => 'lunas'
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }
}