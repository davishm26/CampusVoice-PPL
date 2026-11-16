<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PusatBantuanController;
use App\Http\Controllers\SuperAdminAkunController;
use App\Http\Controllers\SuperAdminKategoriController;
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
Route::get('/laporan/bukti/{tiket}', [LaporanController::class, 'showBukti'])->name('laporan.bukti');
Route::get('/detail-laporan/{tiket?}', [LaporanController::class, 'detail']);
Route::post('/laporan/detail/{tiket}/tanggapan', [LaporanController::class, 'storeTanggapan'])->name('laporan.tanggapan');
Route::post('/laporan/detail/{tiket}/buka-kembali', [LaporanController::class, 'bukaKembali'])->name('laporan.reopen');
Route::post('/laporan/detail/{tiket}/penilaian', [LaporanController::class, 'simpanPenilaian'])->name('laporan.rating');

// Notifikasi
Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');
Route::get('/notif', [NotificationController::class, 'index']);
Route::get('/admin/notifikasi', [NotificationController::class, 'index'])->name('admin.notifikasi');
Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.markAll');
Route::get('/notifikasi/baca/{id}', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');

// Profil Saya (Kustom Dinamis & Update Kontak)
Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
Route::get('/profile-saya', [ProfileController::class, 'show']);
Route::post('/profil/telepon', [ProfileController::class, 'updatePhone'])->name('profil.phone.update');

// Pusat Bantuan
Route::get('/pusat-bantuan', [PusatBantuanController::class, 'index'])->name('pusat.bantuan');
Route::get('/bantuan', fn () => redirect()->route('pusat.bantuan'));

// Admin SSC: Dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('/admin', fn () => redirect()->route('admin.dashboard'));
Route::get('/dashboard-admin', fn () => redirect()->route('admin.dashboard'));

// Admin SSC: Verifikasi Laporan & Detail Verifikasi
Route::get('/admin/verifikasi-laporan', [LaporanController::class, 'adminVerifikasi'])->name('admin.verifikasi');
Route::get('/admin/verifikasi-laporan/{tiket}', [LaporanController::class, 'adminVerifikasiDetail'])->name('admin.verifikasi.detail');
Route::post('/admin/verifikasi-laporan/{tiket}', [LaporanController::class, 'adminVerifikasiSubmit'])->name('admin.verifikasi.submit');
Route::get('/admin/verifikasi', fn () => redirect()->route('admin.verifikasi'));
Route::get('/verifikasi-laporan', fn () => redirect()->route('admin.verifikasi'));

// Admin SSC: Monitoring Laporan
Route::get('/admin/monitoring-laporan', [LaporanController::class, 'adminMonitoring'])->name('admin.monitoring');
Route::get('/admin/monitoring-laporan/{tiket}', [LaporanController::class, 'adminMonitoringDetail'])->name('admin.monitoring.detail');
Route::get('/admin/monitoring', fn () => redirect()->route('admin.monitoring'));
Route::get('/monitoring-laporan', fn () => redirect()->route('admin.monitoring'));

// Admin SSC: Rekapitulasi Pengaduan
Route::get('/admin/rekapitulasi', [LaporanController::class, 'adminRekapitulasi'])->name('admin.rekapitulasi');
Route::get('/rekapitulasi', fn () => redirect()->route('admin.rekapitulasi'));

// Petugas Unit: Dashboard & Notifikasi Unit
Route::get('/unit/dashboard', [DashboardController::class, 'unitDashboard'])->name('unit.dashboard');
Route::get('/dashboard-unit', fn () => redirect()->route('unit.dashboard'));
Route::get('/unit', fn () => redirect()->route('unit.dashboard'));

// Admin SSC: Profil Saya
Route::get('/admin/profil', [ProfileController::class, 'show'])->name('admin.profil');

// Petugas Unit: Laporan Ditugaskan, Notifikasi Unit & Profil Unit
Route::get('/unit/laporan-ditugaskan', [LaporanController::class, 'laporanDitugaskan'])->name('unit.laporan-ditugaskan');
Route::get('/unit/laporan-ditugaskan/{tiket}', [LaporanController::class, 'tanganiLaporan'])->name('unit.laporan-ditugaskan.detail');
Route::post('/unit/laporan-ditugaskan/{tiket}/update', [LaporanController::class, 'updatePenangananUnit'])->name('unit.laporan-ditugaskan.update');
Route::get('/laporan-ditugaskan', fn () => redirect()->route('unit.laporan-ditugaskan'));
Route::get('/petugas/laporan-ditugaskan', fn () => redirect()->route('unit.laporan-ditugaskan'));

Route::get('/unit/notifikasi', [NotificationController::class, 'index'])->name('unit.notifikasi');
Route::get('/notifikasi-unit', fn () => redirect()->route('unit.notifikasi'));

Route::get('/unit/profil', [ProfileController::class, 'show'])->name('unit.profil');
Route::get('/profil-unit', fn () => redirect()->route('unit.profil'));

// Super Admin: Dashboard & Log Aktivitas
Route::get('/super-admin/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
Route::get('/dashboard-super-admin', fn () => redirect()->route('superadmin.dashboard'));

Route::get('/super-admin/log-aktivitas', [DashboardController::class, 'superAdminLog'])->name('superadmin.log');
Route::get('/log-aktivitas', fn () => redirect()->route('superadmin.log'));

Route::get('/super-admin/unit-penangan', [DashboardController::class, 'superAdminUnitPenangan'])->name('superadmin.unit-penangan');
Route::get('/unit-penangan', fn () => redirect()->route('superadmin.unit-penangan'));
Route::post('/super-admin/unit-penangan', [DashboardController::class, 'superAdminUnitPenanganStore'])->name('superadmin.unit-penangan.store');
Route::put('/super-admin/unit-penangan/{id}', [DashboardController::class, 'superAdminUnitPenanganUpdate'])->name('superadmin.unit-penangan.update');
Route::delete('/super-admin/unit-penangan/{id}', [DashboardController::class, 'superAdminUnitPenanganDestroy'])->name('superadmin.unit-penangan.destroy');

// Super Admin: Manajemen Akun
Route::get('/super-admin/manajemen-akun', [SuperAdminAkunController::class, 'index'])->name('superadmin.akun');
Route::get('/manajemen-akun', fn () => redirect()->route('superadmin.akun'));
Route::post('/super-admin/manajemen-akun', [SuperAdminAkunController::class, 'store'])->name('superadmin.akun.store');
Route::put('/super-admin/manajemen-akun/{user}', [SuperAdminAkunController::class, 'update'])->name('superadmin.akun.update');
Route::delete('/super-admin/manajemen-akun/{user}', [SuperAdminAkunController::class, 'destroy'])->name('superadmin.akun.destroy');

// Super Admin: Kategori & Aturan Penyaluran
Route::get('/super-admin/kategori-penyaluran', [SuperAdminKategoriController::class, 'index'])->name('superadmin.kategori');
Route::get('/kategori-penyaluran', fn () => redirect()->route('superadmin.kategori'));
Route::post('/super-admin/kategori-penyaluran', [SuperAdminKategoriController::class, 'store'])->name('superadmin.kategori.store');
Route::put('/super-admin/kategori-penyaluran/{kategoriPenyaluran}', [SuperAdminKategoriController::class, 'update'])->name('superadmin.kategori.update');
Route::delete('/super-admin/kategori-penyaluran/{kategoriPenyaluran}', [SuperAdminKategoriController::class, 'destroy'])->name('superadmin.kategori.destroy');

// Petugas Unit: Riwayat Penanganan
Route::get('/petugas/riwayat-penanganan', [LaporanController::class, 'petugasRiwayat'])->name('petugas.riwayat');
Route::get('/riwayat-penanganan', fn () => redirect()->route('petugas.riwayat'));

// Breeze default profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
