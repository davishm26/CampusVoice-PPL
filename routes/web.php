<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/laporan/buat', function () {
    return view('laporan.create');
})->name('laporan.create');

Route::get('/laporan-baru', function () {
    return view('laporan.create');
});

Route::get('/laporan/sukses', function () {
    return view('laporan.success');
})->name('laporan.success');

Route::get('/laporan-sukses', function () {
    return view('laporan.success');
});

Route::get('/profil', function () {
    return view('profil');
})->name('profil');

Route::get('/profile-saya', function () {
    return view('profil');
});
Route::get('/laporan/riwayat', function () {
    return view('laporan.riwayat');
})->name('laporan.riwayat');

Route::get('/riwayat-laporan', function () {
    return view('laporan.riwayat');
});

Route::get('/riwayat', function () {
    return view('laporan.riwayat');
});

Route::get('/notifikasi', function () {
    return view('notifikasi');
})->name('notifikasi');

Route::get('/notif', function () {
    return view('notifikasi');
});

Route::get('/laporan/detail/{tiket?}', function ($tiket = 'CV-2026-00124') {
    if (view()->exists('laporan.detail')) {
        return view('laporan.detail', compact('tiket'));
    }
    if (view()->exists('laporan.show')) {
        return view('laporan.show', compact('tiket'));
    }
    return redirect()->route('laporan.riwayat');
})->name('laporan.detail');

Route::get('/detail-laporan/{tiket?}', function ($tiket = 'CV-2026-00124') {
    return redirect()->route('laporan.detail', $tiket);
});  

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard-mahasiswa', function () {
    return view('dashboard');
});

Route::get('/beranda', function () {
    return view('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
