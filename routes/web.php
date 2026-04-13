<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Wisatawan\PaketTripController;
use App\Http\Controllers\Wisatawan\ReservasiController;
use App\Http\Controllers\Wisatawan\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage.index');
});

// Landing Page Routes
Route::get('/paket-trip', [PaketTripController::class, 'index'])->name('paket-trip.index');
Route::get('/paket-trip/{id}', [PaketTripController::class, 'show'])->name('paket-trip.show');

// Reservasi Routes
Route::get('/reservasi/create', [ReservasiController::class, 'create'])->middleware('auth')->name('reservasi.create');
Route::post('/reservasi', [ReservasiController::class, 'store'])->middleware('auth')->name('reservasi.store');
Route::get('/reservasi/{id}', [ReservasiController::class, 'show'])->middleware('auth')->name('reservasi.show');
Route::get('/reservasi', [ReservasiController::class, 'index'])->middleware('auth')->name('reservasi.index');

// Pembayaran Routes
Route::get('/pembayaran/{reservasiId}/create', [PembayaranController::class, 'create'])->middleware('auth')->name('pembayaran.create');
Route::match(['get', 'post'], '/pembayaran/verify', [PembayaranController::class, 'verify'])->name('pembayaran.verify');
Route::get('/pembayaran/{id}/status', [PembayaranController::class, 'status'])->middleware('auth')->name('pembayaran.status');
Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->middleware('auth')->name('pembayaran.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
//     Route::post('/users', [AdminController::class, 'store']);
// });

// // Hanya untuk wisatawan
// Route::middleware(['auth', 'role:wisatawan'])->group(function () {
//     Route::get('/paket-trip', [PaketTripController::class, 'index']);
//     Route::post('/reservasi', [ReservasiController::class, 'store']);
// });

// // Multiple roles
// Route::middleware(['auth', 'role:admin,wisatawan'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show']);
// });

require __DIR__.'/auth.php';
