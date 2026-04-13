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

    public function show($id)
    {
        $paket = PaketTrip::findOrFail($id);
        
        // Get related pakets (paket serupa dengan kategori sama, exclude paket ini)
        $paketSerupa = PaketTrip::where('kategori', $paket->kategori)
            ->where('paketId', '!=', $id)
            ->where('aktif', true)
            ->limit(3)
            ->get();

        return view('paket_trip.show', compact('paket', 'paketSerupa'));
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
