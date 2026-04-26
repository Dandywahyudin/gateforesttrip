<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\PaketTripController as AdminPaketTripController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Wisatawan\PaketTripController;
use App\Http\Controllers\Wisatawan\PembayaranController;
use App\Http\Controllers\Wisatawan\ReservasiController;
use App\Events\JadwalKuotaUpdated;
use App\Models\Jadwal;
use App\Models\PaketTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $paketAktif = PaketTrip::where('aktif', true)
        ->latest('created_at')
        ->get();

    $highlightPaket = $paketAktif->first();
    $tripOfTheMonth = PaketTrip::where('aktif', true)
        ->withCount(['jadwals as jadwal_open_count' => function ($query) {
            $query->where('status', 'open');
        }])
        ->orderByDesc('jadwal_open_count')
        ->latest('created_at')
        ->first() ?? $highlightPaket;

    $featuredPakets = $paketAktif
        ->reject(function ($paket) use ($tripOfTheMonth) {
            return $tripOfTheMonth && $paket->paketId === $tripOfTheMonth->paketId;
        })
        ->take(3)
        ->values();
    $catalogPakets = $paketAktif->take(6)->values();
    $landingStats = [
        'total_paket' => $paketAktif->count(),
        'total_jadwal' => Jadwal::where('status', 'open')
            ->whereHas('paketTrip', function ($query) {
                $query->where('aktif', true);
            })
            ->count(),
        'kategori' => $paketAktif->pluck('kategori')->filter()->unique()->count(),
    ];

    return view('landingpage.index', compact('highlightPaket', 'tripOfTheMonth', 'featuredPakets', 'catalogPakets', 'landingStats'));
});

// Landing Page Routes
Route::get('/paket-trip', [PaketTripController::class, 'index'])->name('paket-trip.index');
Route::get('/paket-trip/{paketTrip}', [PaketTripController::class, 'show'])->name('paket-trip.show');
Route::get('/jadwal/{jadwal}/realtime', function (Jadwal $jadwal) {
    return response()->json(
        JadwalKuotaUpdated::fromJadwal($jadwal->fresh(['paketTrip', 'reservasis.pembayaran']))->broadcastWith()
    );
})->name('jadwal.realtime');

Route::middleware(['auth', 'role:wisatawan'])->prefix('reservasi')->name('reservasi.')->group(function () {
    Route::get('/{paketTrip}/jadwal', [ReservasiController::class, 'create'])->name('jadwal');
    Route::post('/jadwal', [ReservasiController::class, 'store'])->name('jadwal.store');

    Route::get('/peserta', [ReservasiController::class, 'createPeserta'])->name('peserta');
    Route::post('/peserta', [ReservasiController::class, 'storePeserta'])->name('peserta.store');

    Route::get('/ringkasan', [ReservasiController::class, 'ringkasan'])->name('ringkasan');
    Route::post('/checkout', [ReservasiController::class, 'checkout'])->name('checkout');

    Route::get('/riwayat', [ReservasiController::class, 'riwayat'])->name('riwayat');

    Route::get('/{kodeReservasi}/pembayaran', [PembayaranController::class, 'show'])->name('pembayaran');
    Route::get('/pembayaran/finish', [PembayaranController::class, 'finish'])->name('pembayaran.finish');
    Route::get('/pembayaran/unfinish', [PembayaranController::class, 'unfinish'])->name('pembayaran.unfinish');
    Route::get('/pembayaran/error', [PembayaranController::class, 'error'])->name('pembayaran.error');
    Route::get('/{kodeReservasi}/tiket', [PembayaranController::class, 'downloadTicket'])->name('tiket');
    Route::get('/{kodeReservasi}/bukti-transaksi', [PembayaranController::class, 'downloadReceipt'])->name('bukti-transaksi');
    Route::get('/{kodeReservasi}/status', [PembayaranController::class, 'status'])->name('status');
});

Route::post('/midtrans/notification', [PembayaranController::class, 'notification'])->name('midtrans.notification');

Route::middleware(['auth', 'verified'])->get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect('/');
})->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [AdminController::class, 'jadwal'])->name('jadwal.index');
    Route::get('/reservasi', [AdminReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/export/pdf', [AdminReservasiController::class, 'exportPdf'])->name('reservasi.export.pdf');
    Route::get('/reservasi/export/csv', [AdminReservasiController::class, 'exportCsv'])->name('reservasi.export.csv');
    Route::get('/reservasi/{reservasi}', [AdminReservasiController::class, 'show'])->name('reservasi.show');
    Route::get('/paket-trip', [AdminPaketTripController::class, 'index'])->name('paket-trip.index');
    Route::get('/paket-trip/create', [AdminPaketTripController::class, 'create'])->name('paket-trip.create');
    Route::post('/paket-trip', [AdminPaketTripController::class, 'store'])->name('paket-trip.store');
    Route::get('/paket-trip/{paketTrip}/edit', [AdminPaketTripController::class, 'edit'])->name('paket-trip.edit');
    Route::put('/paket-trip/{paketTrip}', [AdminPaketTripController::class, 'update'])->name('paket-trip.update');
    Route::delete('/paket-trip/{paketTrip}', [AdminPaketTripController::class, 'destroy'])->name('paket-trip.destroy');

    Route::prefix('/paket-trip/{paketTrip}/jadwal')->name('paket-trip.jadwal.')->group(function () {
        Route::get('/', [AdminJadwalController::class, 'index'])->name('index');
        Route::get('/create', [AdminJadwalController::class, 'create'])->name('create');
        Route::post('/', [AdminJadwalController::class, 'store'])->name('store');
        Route::get('/{jadwalId}/edit', [AdminJadwalController::class, 'edit'])->name('edit');
        Route::put('/{jadwalId}', [AdminJadwalController::class, 'update'])->name('update');
        Route::delete('/{jadwalId}', [AdminJadwalController::class, 'destroy'])->name('destroy');
    });
});

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
