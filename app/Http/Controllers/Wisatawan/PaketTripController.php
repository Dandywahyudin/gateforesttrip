<?php

namespace App\Http\Controllers\Wisatawan;

use Illuminate\Http\Request;
use App\Models\PaketTrip;

class PaketTripController extends Controller
{
    public function index()
    {
        $pakets = PaketTrip::where('aktif', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paket_trip.index', compact('pakets'));
    }

    public function show(PaketTrip $paketTrip)
    {
        $paket = $paketTrip->load(['jadwals' => function ($query) {
                $query->where('status', 'open')
                    ->orderBy('tanggal_berangkat');
            }]);
        
        // Get related pakets (paket serupa dengan kategori sama, exclude paket ini)
        $paketSerupa = PaketTrip::where('kategori', $paket->kategori)
            ->where('paketId', '!=', $paket->paketId)
            ->where('aktif', true)
            ->limit(3)
            ->get();

        $jadwals = $paket->jadwals;

        return view('paket_trip.show', compact('paket', 'paketSerupa', 'jadwals'));
    }

    public function create()
    {
        return view('paket_trip.create');
    }

    public function store(Request $request)
    {
        // Implementation for storing paket trip
    }

    public function edit($id)
    {
        return view('paket_trip.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Implementation for updating paket trip
    }

    public function destroy($id)
    {
        // Implementation for deleting paket trip
    }
}
