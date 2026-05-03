<?php

namespace App\Http\Controllers\Wisatawan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use App\Models\User;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class PembayaranController extends Controller
{
	public function show(string $kodeReservasi, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasi($kodeReservasi);
		$pembayaran = $midtransService->syncPayment($reservasi);
		$this->storeGatewayPaymentContext($reservasi, $pembayaran);

		if (in_array($pembayaran->status, ['settlement', 'capture'], true) || $reservasi->status === 'paid') {
			return redirect()->route('reservasi.status', $reservasi->kode_reservasi);
		}

		return view('wisatawan.pembayaran.create', compact('reservasi', 'pembayaran'));
	}

	public function finish(Request $request, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasiFromGateway($request);

		if (! $reservasi) {
			return redirect()->route('dashboard')->with('error', 'Data pembayaran tidak ditemukan pada tautan pembayaran.');
		}

		$pembayaran = $midtransService->refreshPaymentStatus($reservasi);

		$message = $this->isPaymentCompleted($reservasi, $pembayaran)
			? 'Pembayaran berhasil diproses. Berikut status reservasi Anda.'
			: 'Pembayaran belum terkonfirmasi sepenuhnya. Status reservasi akan tetap diperbarui.';

		return redirect()
			->route('reservasi.status', $reservasi->kode_reservasi)
			->with('success', $message);
	}

	public function unfinish(Request $request, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasiFromGateway($request);

		if (! $reservasi) {
			return redirect()->route('dashboard')->with('error', 'Data pembayaran tidak ditemukan pada tautan pembayaran.');
		}

		$midtransService->refreshPaymentStatus($reservasi);

		return redirect()
			->route('reservasi.status', $reservasi->kode_reservasi)
			->with('error', 'Pembayaran belum selesai. Silakan cek status reservasi Anda.');
	}

	public function error(Request $request, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasiFromGateway($request);

		if (! $reservasi) {
			return redirect()->route('dashboard')->with('error', 'Data pembayaran tidak ditemukan pada tautan pembayaran.');
		}

		$midtransService->refreshPaymentStatus($reservasi);

		return redirect()
			->route('reservasi.status', $reservasi->kode_reservasi)
			->with('error', 'Terjadi kendala saat memproses pembayaran. Silakan cek status reservasi Anda.');
	}

	public function status(string $kodeReservasi, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasi($kodeReservasi);
		$pembayaran = $midtransService->refreshPaymentStatus($reservasi);
		$adminWhatsappUrl = $this->resolveAdminWhatsappUrl($reservasi);

		return view('wisatawan.pembayaran.status', compact('reservasi', 'pembayaran', 'adminWhatsappUrl'));
	}

	public function downloadTicket(string $kodeReservasi, MidtransService $midtransService)
	{
		return $this->downloadCombinedDocument($kodeReservasi, $midtransService);
	}

	public function downloadReceipt(string $kodeReservasi, MidtransService $midtransService)
	{
		return $this->downloadCombinedDocument($kodeReservasi, $midtransService);
	}

	public function notification(Request $request, MidtransService $midtransService)
	{
		try {
			$pembayaran = $midtransService->handleNotification();

			if (! $pembayaran) {
				Log::warning('Pembayaran tidak ditemukan', $request->all());
			}

			return response()->json(['message' => 'OK'], 200);

		} catch (\Exception $e) {
			Log::error('Midtrans Callback Error: ' . $e->getMessage());

			return response()->json(['message' => 'Error handled'], 200);
		}
	}

	private function isPaymentCompleted(Reservasi $reservasi, Pembayaran $pembayaran): bool
	{
		return in_array($pembayaran->status, ['settlement', 'capture'], true) || $reservasi->status === 'paid';
	}

	private function downloadCombinedDocument(string $kodeReservasi, MidtransService $midtransService)
	{
		$reservasi = $this->resolveReservasi($kodeReservasi);
		$pembayaran = $midtransService->refreshPaymentStatus($reservasi);

		if (! $this->isPaymentCompleted($reservasi, $pembayaran)) {
			return redirect()
				->route('reservasi.status', $reservasi->kode_reservasi)
				->with('error', 'Dokumen hanya bisa diunduh setelah pembayaran selesai.');
		}

		$pdf = Pdf::loadView('wisatawan.pembayaran.pdf.bukti-transaksi', [
			'reservasi' => $reservasi,
			'pembayaran' => $pembayaran,
		])->setPaper('a4', 'portrait');

		return $pdf->download('dokumen-' . $reservasi->kode_reservasi . '.pdf');
	}

	private function resolveReservasi(string $kodeReservasi): Reservasi
	{
		return Reservasi::with(['jadwal.paketTrip', 'user', 'peserta', 'pembayaran'])
			->where('kode_reservasi', $kodeReservasi)
			->where('userId', Auth::id())
			->firstOrFail();
	}

	private function resolveReservasiFromGateway(?Request $request = null): ?Reservasi
	{
		$kodeReservasi = $request?->query('kode_reservasi');
		$orderId = session('midtrans_payment.order_id');

		if ($kodeReservasi) {
			$reservasi = Reservasi::with(['jadwal.paketTrip', 'user', 'peserta', 'pembayaran'])
				->where('kode_reservasi', $kodeReservasi)
				->where('userId', Auth::id())
				->first();

			if ($reservasi) {
				return $reservasi;
			}
		}

		if ($orderId) {
			$pembayaran = Pembayaran::with(['reservasi.jadwal.paketTrip'])
				->where('orderId', $orderId)
				->first();

			if ($pembayaran?->reservasi && (int) $pembayaran->reservasi->userId === (int) Auth::id()) {
				return $pembayaran->reservasi;
			}
		}

		$kodeReservasi = session('midtrans_payment.kode_reservasi');

		if ($kodeReservasi) {
			$reservasi = Reservasi::with(['jadwal.paketTrip', 'user', 'peserta', 'pembayaran'])
				->where('kode_reservasi', $kodeReservasi)
				->where('userId', Auth::id())
				->first();

			if ($reservasi) {
				return $reservasi;
			}
		}

		return null;
	}

	private function storeGatewayPaymentContext(Reservasi $reservasi, Pembayaran $pembayaran): void
	{
		session()->put('midtrans_payment', [
			'order_id' => $pembayaran->orderId,
			'kode_reservasi' => $reservasi->kode_reservasi,
		]);
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
