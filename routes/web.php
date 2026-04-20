<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Pasien\PasienController as PasienUserController;
use App\Http\Controllers\Admin\PasienController as PasienAdminController;
use App\Http\Controllers\Admin\DokterController as DokterAdminController;

use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Dokter\DokterController as DokterUserController;
use App\Http\Controllers\AntrianDisplayController;

use App\Http\Controllers\Pasien\PembayaranController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;


// ================= HOME =================
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
    Route::resource('dokter', DokterAdminController::class);

    Route::resource('pasien', PasienAdminController::class);

    // EXPORT
    Route::get('/export-dokter', [AdminController::class, 'exportDokter'])
        ->name('admin.export.dokter');

    Route::get('/export-pasien', [AdminController::class, 'exportPasien'])
        ->name('admin.export.pasien');

    Route::get('/export-obat', [AdminController::class, 'exportObat'])
        ->name('admin.export.obat');

    // PEMBAYARAN
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])
        ->name('admin.pembayaran');

    Route::post('/pembayaran/konfirmasi/{id}', [AdminPembayaranController::class, 'konfirmasi'])
        ->name('admin.pembayaran.konfirmasi');

});


// ================= DOKTER =================
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {

    Route::get('/dashboard', [DokterUserController::class, 'dashboard'])
        ->name('dokter.dashboard');

    Route::post('/panggil/{id}', [DokterUserController::class, 'panggil'])
        ->name('dokter.panggil');

    Route::get('/periksa/{id}', [DokterUserController::class, 'formPeriksa'])
        ->name('dokter.periksa');

    Route::post('/periksa/{id}', [DokterUserController::class, 'simpanPeriksa'])
        ->name('dokter.periksa.simpan');

    Route::get('/riwayat/{id}', [DokterUserController::class, 'riwayat'])
        ->name('dokter.riwayat');

    Route::get('/struk/{id}', [DokterUserController::class, 'struk'])
        ->name('dokter.struk');

    Route::get('/export-jadwal', [DokterUserController::class, 'exportJadwal'])
        ->name('dokter.export.jadwal');

    Route::get('/export-riwayat', [DokterUserController::class, 'exportRiwayat'])
        ->name('dokter.export.riwayat');

    Route::post('/selesai/{id}', [DokterUserController::class, 'selesai'])
        ->name('dokter.selesai');
});

// ================= PASIEN =================
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {

    Route::get('/dashboard', [PasienUserController::class, 'dashboard'])
        ->name('pasien.dashboard');

    Route::post('/daftar', [PasienUserController::class, 'daftar'])
        ->name('pasien.daftar');

    Route::get('/riwayat', [PasienUserController::class, 'riwayat'])
        ->name('pasien.riwayat');

    Route::get('/detail/{id}', [PasienUserController::class, 'detail'])
        ->name('pasien.detail');
});


// ================= PEMBAYARAN PASIEN =================
Route::middleware(['auth'])->prefix('pasien')->group(function () {

    Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->name('pasien.pembayaran');

    Route::post('/pembayaran/upload/{id}', [PembayaranController::class, 'upload'])
        ->name('pasien.pembayaran.upload');

});


// ================= API ANTRIAN =================
Route::get('/antrian/live', function () {

    $periksa = \App\Models\Periksa::with('daftarPoli.jadwal.poli','daftarPoli.jadwal.dokter')
        ->latest('created_at')
        ->first();

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


// ================= DISPLAY =================
Route::get('/display', [AntrianDisplayController::class, 'index']);
Route::get('/display/data', [AntrianDisplayController::class, 'data']);