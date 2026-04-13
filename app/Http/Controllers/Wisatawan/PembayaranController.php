<?php

namespace App\Http\Controllers\Wisatawan;

use App\Models\Reservasi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function create($reservasiId)
    {
        // Get reservasi with all related data
        $reservasi = Reservasi::with(['user', 'jadwal.paketTrip', 'peserta'])->findOrFail($reservasiId);
        
        // Check authorization
        if ($reservasi->userId !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if already paid
        if ($reservasi->status === 'paid') {
            return redirect()->route('reservasi.show', $reservasiId)
                ->with('info', 'Reservasi ini sudah dibayar');
        }
        
        // Get or create payment record
        $pembayaran = $reservasi->pembayaran ?? Pembayaran::create([
            'reservasiId' => $reservasiId,
            'orderId' => 'ORD-' . strtoupper(Str::random(12)),
            'jumlah' => $reservasi->total_harga,
            'metode_pembayaran' => 'midtrans',
            'status' => 'pending',
            'paid_at' => null,
        ]);
        
        // Prepare payment payload for Midtrans
        $payload = [
            'transaction_details' => [
                'order_id' => $pembayaran->orderId,
                'gross_amount' => (int)$reservasi->total_harga,
            ],
            'customer_details' => [
                'first_name' => $reservasi->user->nama,
                'email' => $reservasi->user->email,
                'phone' => $reservasi->peserta->first()?->no_hp ?? '',
            ],
            'item_details' => [
                [
                    'id' => $reservasi->jadwal->paketId,
                    'price' => (int)($reservasi->jadwal->harga_override ?? $reservasi->jadwal->paketTrip->harga),
                    'quantity' => $reservasi->jml_peserta,
                    'name' => $reservasi->jadwal->paketTrip->nama,
                ]
            ],
            'callbacks' => [
                'finish' => route('pembayaran.status', $pembayaran->pembayaranId),
                'unfinish' => route('pembayaran.status', $pembayaran->pembayaranId),
                'error' => route('pembayaran.status', $pembayaran->pembayaranId),
            ]
        ];
        
        return view('wisatawan.pembayaran.create', compact('reservasi', 'pembayaran', 'payload'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'status_code' => 'required|string',
            'gross_amount' => 'required|numeric',
        ]);

        // Find payment by orderId
        $pembayaran = Pembayaran::where('orderId', $request->order_id)->firstOrFail();
        $reservasi = $pembayaran->reservasi;

        // Update payment status based on Midtrans response
        $status_code = $request->status_code;
        
        if ($status_code == '200' || $status_code == '201') {
            // Payment success
            $pembayaran->update([
                'status' => 'settlement',
                'paid_at' => Carbon::now(),
            ]);
            
            $reservasi->update(['status' => 'paid']);
            
            return redirect()->route('pembayaran.status', $pembayaran->pembayaranId)
                ->with('success', 'Pembayaran berhasil!');
        } else {
            // Payment failed or pending
            $pembayaran->update(['status' => 'failed']);
            
            return redirect()->route('pembayaran.status', $pembayaran->pembayaranId)
                ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
        }
    }

    public function status($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $reservasi = $pembayaran->reservasi;
        
        // Check authorization
        if ($reservasi->userId !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        return view('wisatawan.pembayaran.status', compact('pembayaran', 'reservasi'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $reservasi = $pembayaran->reservasi;
        
        // Check authorization
        if ($reservasi->userId !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        return view('wisatawan.pembayaran.show', compact('pembayaran', 'reservasi'));
    }
}
