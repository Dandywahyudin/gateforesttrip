<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Jadwal;
use App\Models\PaketTrip;
use App\Models\Reservasi;
use Illuminate\View\View;

class AdminController extends BaseController
{
    public function dashboard(): View
    {
        $stats = [
            'total_paket' => PaketTrip::count(),
            'paket_aktif' => PaketTrip::where('aktif', true)->count(),
            'total_jadwal' => Jadwal::count(),
            'total_reservasi' => Reservasi::count(),
            'reservasi_paid' => Reservasi::where('status', 'paid')->count(),
            'omzet' => (float) Reservasi::where('status', 'paid')
                ->whereHas('pembayaran', function ($query) {
                    $query->whereIn('status', ['settlement', 'capture']);
                })
                ->sum('total_harga'),
        ];

        $paketTrips = PaketTrip::withCount([
                'jadwals as jadwal_open_count' => function ($query) {
                    $query->where('status', 'open');
                },
                'reservasis as reservasi_count',
            ])
            ->latest('paketId')
            ->limit(6)
            ->get();

        $reservasis = Reservasi::with(['jadwal.paketTrip', 'pembayaran', 'user'])
            ->latest('reservasiId')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'paketTrips', 'reservasis'));
    }

    public function jadwal(): View
    {
        $jadwals = Jadwal::with('paketTrip')
            ->orderBy('tanggal_berangkat')
            ->get();

        return view('admin.jadwal', compact('jadwals'));
    }

    public function paketTrip(): View
    {
        $paketTrips = PaketTrip::withCount([
                'jadwals as jadwal_open_count' => function ($query) {
                    $query->where('status', 'open');
                },
            ])
            ->latest('paketId')
            ->get();

        return view('admin.paket-trip.index', compact('paketTrips'));
    }
}
