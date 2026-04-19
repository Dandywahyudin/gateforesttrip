<?php

namespace App\Http\Controllers\Wisatawan;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\User;
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
		$adminWhatsappUrl = $this->resolveAdminWhatsappUrl($reservasi);

		return view('wisatawan.pembayaran.status', compact('reservasi', 'pembayaran', 'adminWhatsappUrl'));
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

	private function resolveAdminWhatsappUrl(Reservasi $reservasi): ?string
	{
		$admin = User::query()
			->where('role', 'admin')
			->whereNotNull('no_hp')
			->orderBy('userId')
			->first();

		if (! $admin?->no_hp) {
			return null;
		}

		$phoneNumber = preg_replace('/\D+/', '', (string) $admin->no_hp);

		if ($phoneNumber === '') {
			return null;
		}

		if (str_starts_with($phoneNumber, '0')) {
			$phoneNumber = '62' . substr($phoneNumber, 1);
		} elseif (str_starts_with($phoneNumber, '8')) {
			$phoneNumber = '62' . $phoneNumber;
		}

		$message = sprintf(
			'Halo Admin GateForestTrip, saya ingin menanyakan reservasi %s untuk paket %s.',
			$reservasi->kode_reservasi,
			$reservasi->jadwal?->paketTrip?->nama ?? 'Open Trip'
		);

		return 'https://wa.me/' . $phoneNumber . '?text=' . rawurlencode($message);
	}
}
