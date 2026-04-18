<?php

namespace App\Http\Controllers\Wisatawan;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
	public function show(string $kodeReservasi, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasi($kodeReservasi);
		$pembayaran = $midtransService->syncPayment($reservasi);

		if (in_array($pembayaran->status, ['settlement', 'capture'], true) || $reservasi->status === 'paid') {
			return redirect()->route('reservasi.status', $reservasi->kode_reservasi);
		}

		return view('wisatawan.pembayaran.create', compact('reservasi', 'pembayaran'));
	}

	// public function show(string $kodeReservasi, MidtransService $midtransService)
	// {
	// 	$reservasi = $this->resolveReservasi($kodeReservasi);
	// 	$pembayaran = $reservasi->pembayaran;

	// 	if ($pembayaran && $pembayaran->expired_at && now()->gt($pembayaran->expired_at)) {
    //     $pembayaran->update(['status' => 'expire']);
    //     $reservasi->update(['status' => 'expired']);

    //     return redirect()->route('reservasi.status', $reservasi->kode_reservasi)
    //         ->with('error', 'Waktu pembayaran telah habis.');
    // }

	// 	$pembayaran = $midtransService->syncPayment($reservasi);
	// if (in_array($pembayaran->status, ['settlement', 'capture'], true) || $reservasi->status === 'paid') {
	// 	return redirect()->route('reservasi.status', $reservasi->kode_reservasi);
	// }

	// 	return view('wisatawan.pembayaran.create', compact('reservasi', 'pembayaran'));
	// }

	public function status(string $kodeReservasi, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasi($kodeReservasi);
		$pembayaran = $midtransService->refreshPaymentStatus($reservasi);

		return view('wisatawan.pembayaran.status', compact('reservasi', 'pembayaran'));
	}

	public function notification(Request $request, MidtransService $midtransService)
	{
		$pembayaran = $midtransService->handleNotification();

		if (! $pembayaran) {
			return response()->json(['message' => 'Pembayaran tidak ditemukan.'], 404);
		}

		return response()->json(['message' => 'Notification processed successfully.']);
	}

	private function resolveReservasi(string $kodeReservasi): Reservasi
	{
		return Reservasi::with(['jadwal.paketTrip', 'user', 'peserta', 'pembayaran'])
			->where('kode_reservasi', $kodeReservasi)
			->where('userId', Auth::id())
			->firstOrFail();
	}
}
