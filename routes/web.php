<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PusatBantuanController;
use Illuminate\Support\Facades\Route;

// Halaman Depan / Welcome
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Mahasiswa (Dinamis dari Database)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard-mahasiswa', [DashboardController::class, 'index']);
Route::get('/beranda', [DashboardController::class, 'index']);

// Fitur Laporan (Buat, Simpan, Riwayat, Detail, Tanggapan)
Route::get('/laporan/buat', [LaporanController::class, 'create'])->name('laporan.create');
Route::get('/laporan-baru', [LaporanController::class, 'create']);
Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');

Route::get('/laporan/sukses/{tiket?}', [LaporanController::class, 'success'])->name('laporan.success');
Route::get('/laporan-sukses', [LaporanController::class, 'success']);

Route::get('/laporan/riwayat', [LaporanController::class, 'riwayat'])->name('laporan.riwayat');
Route::get('/riwayat-laporan', [LaporanController::class, 'riwayat']);
Route::get('/riwayat', [LaporanController::class, 'riwayat']);

Route::get('/laporan/detail/{tiket?}', [LaporanController::class, 'detail'])->name('laporan.detail');
Route::get('/detail-laporan/{tiket?}', [LaporanController::class, 'detail']);
Route::post('/laporan/detail/{tiket}/tanggapan', [LaporanController::class, 'storeTanggapan'])->name('laporan.tanggapan');
Route::post('/laporan/detail/{tiket}/buka-kembali', [LaporanController::class, 'bukaKembali'])->name('laporan.reopen');
Route::post('/laporan/detail/{tiket}/penilaian', [LaporanController::class, 'simpanPenilaian'])->name('laporan.rating');

// Notifikasi
Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');
Route::get('/notif', [NotificationController::class, 'index']);
Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.markAll');
Route::get('/notifikasi/baca/{id}', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');

// Profil Saya (Kustom Dinamis & Update Kontak)
Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
Route::get('/profile-saya', [ProfileController::class, 'show']);
Route::post('/profil/telepon', [ProfileController::class, 'updatePhone'])->name('profil.phone.update');

// Pusat Bantuan
Route::get('/pusat-bantuan', [PusatBantuanController::class, 'index'])->name('pusat.bantuan');
Route::get('/bantuan', fn () => redirect()->route('pusat.bantuan'));

// Admin SSC: Verifikasi Laporan & Detail Verifikasi
Route::get('/admin/verifikasi-laporan', function () {
    return view('admin.verifikasi');
})->name('admin.verifikasi');
Route::get('/admin/verifikasi-laporan/{tiket}', function ($tiket) {
    return view('admin.verifikasi-detail', compact('tiket'));
})->name('admin.verifikasi.detail');
Route::get('/admin/verifikasi', fn () => redirect()->route('admin.verifikasi'));
Route::get('/verifikasi-laporan', fn () => redirect()->route('admin.verifikasi'));

// Breeze default profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
