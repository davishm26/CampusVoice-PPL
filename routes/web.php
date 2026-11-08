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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
