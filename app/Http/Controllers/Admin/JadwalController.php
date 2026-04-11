<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPeriksa::with('poli', 'dokter')->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $polis = Poli::all();
        $dokters = User::where('role', 'dokter')->get();

        return view('admin.jadwal.create', compact('polis', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_poli' => 'required',
            'id_dokter' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        JadwalPeriksa::create($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);
        $polis = Poli::all();
        $dokters = User::where('role', 'dokter')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'polis', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);

        $request->validate([
            'id_poli' => 'required',
            'id_dokter' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}