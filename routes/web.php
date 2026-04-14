<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pasien\PasienController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Dokter\DokterController;
use App\Http\Controllers\AntrianDisplayController;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Http\Controllers\Pasien\PembayaranController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;


Route::get('/', function () {
    return view('welcome');
});


// ================= AUTH =================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::resource('polis', PoliController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::resource('obat', \App\Http\Controllers\Admin\ObatController::class);

    // 🔥 EXPORT
    Route::get('/export-dokter', [AdminController::class, 'exportDokter'])
        ->name('admin.export.dokter');

    Route::get('/export-pasien', [AdminController::class, 'exportPasien'])
        ->name('admin.export.pasien');

    Route::get('/export-obat', [AdminController::class, 'exportObat'])
        ->name('admin.export.obat');

    // 🔥 PEMBAYARAN
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])
        ->name('admin.pembayaran');

    Route::post('/pembayaran/konfirmasi/{id}', [AdminPembayaranController::class, 'konfirmasi'])
        ->name('admin.pembayaran.konfirmasi');

});


// ================= DOKTER =================
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {

    Route::get('/dashboard', [DokterController::class, 'dashboard'])
        ->name('dokter.dashboard');

    Route::post('/panggil/{id}', [DokterController::class, 'panggil'])
        ->name('dokter.panggil');

    Route::get('/periksa/{id}', [DokterController::class, 'formPeriksa'])
        ->name('dokter.periksa');

    Route::post('/periksa/{id}', [DokterController::class, 'simpanPeriksa'])
        ->name('dokter.periksa.simpan');

    Route::get('/riwayat/{id}', [DokterController::class, 'riwayat'])
        ->name('dokter.riwayat');

    Route::get('/struk/{id}', [DokterController::class, 'struk'])
        ->name('dokter.struk'); 

    Route::get('/riwayat/{id}', [DokterController::class, 'riwayat'])
    ->name('dokter.riwayat');

    Route::get('/export-jadwal', [DokterController::class, 'exportJadwal'])
    ->name('dokter.export.jadwal');

    Route::get('/export-riwayat', [DokterController::class, 'exportRiwayat'])
        ->name('dokter.export.riwayat');

    Route::post('/selesai/{id}', [DokterController::class, 'selesai'])
    ->name('dokter.selesai');

    Route::post('/dokter/periksa/{id}', [DokterController::class, 'simpanPeriksa'])
    ->name('dokter.simpanPeriksa');
});


// ================= PASIEN =================
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {

    Route::get('/dashboard', [PasienController::class, 'dashboard'])
        ->name('pasien.dashboard');

    Route::post('/daftar', [PasienController::class, 'daftar'])
        ->name('pasien.daftar');

    Route::get('/riwayat', [PasienController::class, 'riwayat'])
        ->name('pasien.riwayat');

    Route::get('/pasien/riwayat', [PasienController::class, 'riwayat'])->name('pasien.riwayat');
    Route::get('/pasien/detail/{id}', [PasienController::class, 'detail'])->name('pasien.detail');
    
});

// ================= PEMBAYARAN =================
Route::middleware(['auth'])->prefix('pasien')->group(function () {

    Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->name('pasien.pembayaran');

    Route::post('/pembayaran/upload/{id}', [PembayaranController::class, 'upload'])
        ->name('pasien.pembayaran.upload');

});

// ================= API LIVE ANTRIAN (FINAL) =================
Route::get('/antrian/live', function () {

    // 🔴 AMBIL PERIKSA TERBARU (INI KUNCINYA)
    $periksa = \App\Models\Periksa::with('daftarPoli.jadwal.poli','daftarPoli.jadwal.dokter')
        ->latest('created_at') // 🔥 pakai created_at periksa
        ->first();

    // 🟢 PER JADWAL
    $perJadwal = \App\Models\JadwalPeriksa::with(['daftarPoli.periksa'])
        ->get()
        ->map(function($j){

            $last = $j->daftarPoli
                ->filter(fn($d) => $d->periksa)
                ->sortByDesc(fn($d) => $d->periksa->created_at)
                ->first();

            return [
                'id' => $j->id,
                'nomor' => $last->no_antrian ?? null
            ];
        });

    return response()->json([
        'no_antrian' => $periksa?->daftarPoli?->no_antrian,
        'jadwal' => $periksa?->daftarPoli?->jadwal,
        'per_jadwal' => $perJadwal
    ]);

})->name('antrian.live');


// ================= DISPLAY TV =================
Route::get('/display', [AntrianDisplayController::class, 'index']);
Route::get('/display/data', [AntrianDisplayController::class, 'data']);