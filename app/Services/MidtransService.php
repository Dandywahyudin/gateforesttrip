<?php

namespace App\Services;

use App\Events\JadwalKuotaUpdated;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
	public function syncPayment(Reservasi $reservasi): Pembayaran
	{
		$reservasi->loadMissing(['jadwal.paketTrip', 'user', 'pembayaran']);

		$pembayaran = $reservasi->pembayaran;

		if (! $pembayaran) {
			$pembayaran = Pembayaran::create([
				'reservasiId' => $reservasi->reservasiId,
				'orderId' => 'ORD-' . $reservasi->kode_reservasi,
				'jumlah' => $reservasi->total_harga,
				'status' => 'pending',
				'expired_at' => now()->addMinutes(10),
			]);
		}

		if (empty($pembayaran->snap_token)) {
			$snapToken = $this->createSnapToken($reservasi, $pembayaran->orderId);

			if ($snapToken) {
				$pembayaran->snap_token = $snapToken;
				$pembayaran->save();
			}
		}

		return $pembayaran->fresh();
	}

	//membuat snap token midtrans untuk proses bayar
	public function createSnapToken(Reservasi $reservasi, ?string $orderId = null): ?string
	{
		try {
			$this->configure();
			$resolvedOrderId = $orderId ?? ('ORD-' . $reservasi->kode_reservasi);

			//payload transaksi yang dikirim ke midtrans untuk membuat snap token
			$payload = [
				//informasi transaksi
				'transaction_details' => [
					'order_id' => $resolvedOrderId,
					'gross_amount' => (int) round($reservasi->total_harga),
				],
				// informasi waktu kadaluarsa pembayaran
				'expiry' => [
					'start_time' => now('Asia/Jakarta')->format('Y-m-d H:i:s O'),
					'unit' => 'minute',
					'duration' => 10,
				],

				'customer_details' => [
					'first_name' => $reservasi->user?->nama ?? 'Wisatawan',
					'email' => $reservasi->user?->email ?? 'customer@example.com',
					'phone' => $reservasi->user?->no_hp ?? '-',
				],
				'item_details' => [
					[
						'id' => (string) $reservasi->jadwalId,
						'price' => (int) round($reservasi->total_harga),
						'quantity' => 1,
						'name' => $reservasi->jadwal?->paketTrip?->nama ?? 'Open Trip',
					],
				],
				//endpoint notifikasi midtrans untuk update status pembayaran
				'notification_url' => route('midtrans.notification'),
				'callbacks' => [
					'finish' => route('reservasi.pembayaran.finish'),
				],
                
			];

			return Snap::getSnapToken($payload);
		} catch (\Throwable $throwable) {
			// simpan log error jika gagal membuat snap token
			Log::warning('Gagal membuat Snap token reservasi.', [
				'reservasiId' => $reservasi->reservasiId,
				'kode_reservasi' => $reservasi->kode_reservasi,
				'message' => $throwable->getMessage(),
			]);

			return null;
		}
	}

	//Memproses notifikasi webhook dari midtrans
	public function handleNotification(): ?Pembayaran
	{
		try {
			$this->configure();
			// ambil notifikasi dari midtrans
			$notification = new Notification();
			// $orderId = $notification->order_id ?? $notification->transaction_id ?? null;
			// ambil orderId dari notifikasi midtrans
			$orderId = $notification->order_id ??  null;
			//validasi signature key untuk memastikan notifikasi berasal dari midtrans
			if (! $orderId) {
				Log::warning('Notifikasi Midtrans diabaikan: order_id tidak ditemukan pada payload.', [
					'payload' => $notification,
				]);
				return null;
			}

			if (! $this->isValidSignature(
				$orderId,
				$notification->status_code ?? null,
				$notification->gross_amount ?? null,
				$notification->signature_key ?? null
			)) {
				Log::warning('Notifikasi Midtrans ditolak: signature_key tidak valid.', [
					'orderId' => $orderId,
				]);
 
				return null;
			}

			$pembayaran = Pembayaran::with('reservasi')->where('orderId', $orderId)->first();

			if (! $pembayaran) {
				Log::warning('Notifikasi Midtrans diabaikan: pembayaran tidak ditemukan.', [
					'orderId' => $orderId,
				]);
 
				return null;
			}
			
			// return $this->applyGatewayStatus($pembayaran, $notification);
			$gatewayResponse = Transaction::status($orderId);
			return $this->applyGatewayStatus($pembayaran, $gatewayResponse);
			} catch (\Throwable $throwable) {
			Log::error('Midtrans notification gagal diproses.', [
				'message' => $throwable->getMessage(),
			]);

			return null;
		}
	}
	// verifikasi signature dari midtrans
	private function isValidSignature(
		?string $orderId,
		?string $statusCode,
		?string $grossAmount,
		?string $signatureKey
	): bool {
		if (! $orderId || ! $statusCode || $grossAmount === null || ! $signatureKey) {
			return false;
		}
 
		$serverKey = (string) config('services.midtrans.server_key');
 
		if ($serverKey === '') {
			Log::error('Verifikasi signature Midtrans dibatalkan: server key belum dikonfigurasi.');
 
			return false;
		}
 
		$expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
 
		return hash_equals($expectedSignature, $signatureKey);
	}
 
	//  sinkronisasi status pembayaran dari midtrans
	public function refreshPaymentStatus(Reservasi $reservasi): Pembayaran
	{
		$reservasi->loadMissing(['jadwal.paketTrip', 'user', 'pembayaran']);

		$pembayaran = $reservasi->pembayaran;

		if (! $pembayaran || empty($pembayaran->snap_token)) {
			$pembayaran = $this->syncPayment($reservasi);
		}

		if ($this->shouldExpireLocally($pembayaran)) {
			return $this->expirePaymentLocally($pembayaran);
		}

		if (in_array($pembayaran->status, ['expire', 'cancel', 'deny', 'refund', 'partial_refund', 'chargeback'], true) || $pembayaran->reservasi?->status === 'cancelled') {
			return $pembayaran->fresh(['reservasi.jadwal.paketTrip']);
		}

		if (empty($pembayaran->snap_token)) {
			return $pembayaran->fresh();
		}

		try {
			$this->configure();

			$gatewayResponse = Transaction::status($pembayaran->orderId);

			return $this->applyGatewayStatus($pembayaran, $gatewayResponse);
		} catch (\Throwable $throwable) {
			Log::warning('Gagal sinkron status Midtrans dari gateway.', [
				'orderId' => $pembayaran->orderId,
				'message' => $throwable->getMessage(),
			]);

			return $pembayaran->fresh();
		}
	}

	private function shouldExpireLocally(Pembayaran $pembayaran): bool
	{
		return $pembayaran->status === 'pending'
			&& $pembayaran->expired_at !== null
			&& $pembayaran->expired_at->lte(now());
	}

	private function expirePaymentLocally(Pembayaran $pembayaran): Pembayaran
	{
		return DB::transaction(function () use ($pembayaran) {
			$pembayaran = Pembayaran::with(['reservasi.jadwal.paketTrip'])
				->lockForUpdate()
				->findOrFail($pembayaran->pembayaranId);

			if ($pembayaran->status !== 'pending' || ! $pembayaran->reservasi) {
				return $pembayaran->fresh(['reservasi.jadwal.paketTrip']);
			}

			$pembayaran->update([
				'status' => 'expire',
			]);

			$reservasi = $pembayaran->reservasi;

			if ($reservasi->status !== 'paid') {
				$reservasi->update([
					'status' => 'cancelled',
				]);

				$jadwal = Jadwal::whereKey($reservasi->jadwalId)
					->lockForUpdate()
					->first();

				if ($jadwal) {
					$jadwal = $jadwal->syncQuotaFromActiveReservations();

					if ($jadwal) {
						event(JadwalKuotaUpdated::fromJadwal($jadwal));
					}
				}
			}

			return $pembayaran->fresh(['reservasi.jadwal.paketTrip']);
		});
	}

	//memperbarui status pembayaran, reservasi, dan kuota jadwal berdasarkan notifikasi dari midtrans
	private function applyGatewayStatus(Pembayaran $pembayaran, mixed $gatewayResponse): Pembayaran
	{
		return DB::transaction(function () use ($pembayaran, $gatewayResponse) {
			//lock data untuk mencegah race condition
			$pembayaran = Pembayaran::with(['reservasi.jadwal.paketTrip'])
				->lockForUpdate()
				->findOrFail($pembayaran->pembayaranId);

			$gatewayData = json_decode(json_encode($gatewayResponse), true) ?: [];
			$transactionStatus = $gatewayData['transaction_status'] ?? null;
			$fraudStatus = $gatewayData['fraud_status'] ?? null;
			$paymentType = $gatewayData['payment_type'] ?? null;
			$paidAt = $pembayaran->paid_at;
			$status = $pembayaran->status ?? 'pending';
			$reservasiStatus = $pembayaran->reservasi?->status ?? 'unpaid';
			$jadwalDiperbarui = false;
			$isManuallyCancelled = $pembayaran->reservasi?->status === 'cancelled';

			if ($isManuallyCancelled && in_array($transactionStatus, ['settlement', 'capture'], true)) {
				return $pembayaran->fresh(['reservasi.jadwal.paketTrip']);
			}

			// status pembayaran dan reservasi berdasarkan status transaksi dari Midtrans
			if ($transactionStatus === 'settlement') {
				$status = 'settlement';
				$reservasiStatus = 'paid';
				$paidAt = $gatewayData['settlement_time'] ?? $gatewayData['transaction_time'] ?? now();
			} elseif ($transactionStatus === 'capture') {
				if ($fraudStatus === 'challenge') {
					$status = 'pending';
					$reservasiStatus = 'unpaid';
				} else {
					$status = 'capture';
					$reservasiStatus = 'paid';
					$paidAt = $gatewayData['settlement_time'] ?? $gatewayData['transaction_time'] ?? now();
				}
			} elseif ($transactionStatus === 'pending') {
				$status = 'pending';
				$reservasiStatus = 'unpaid';
			} elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
				$status = $transactionStatus;
				$reservasiStatus = 'cancelled';
			} elseif (in_array($transactionStatus, ['refund', 'partial_refund', 'chargeback'], true)) {
				$status = 'cancel';
				$reservasiStatus = 'cancelled';
			}

			$pembayaran->update([
				'status' => $status,
				'paid_at' => $paidAt instanceof Carbon ? $paidAt : (is_string($paidAt) ? Carbon::parse($paidAt) : $paidAt),
				'metode_pembayaran' => $paymentType ?? $pembayaran->metode_pembayaran,
			]);

			if ($pembayaran->reservasi) {
				$reservasi = $pembayaran->reservasi;

				 // Lock data untuk mencegah race condition
				if (in_array($transactionStatus, ['settlement', 'capture'], true) && $reservasi->status !== 'paid') {
					$jadwal = Jadwal::whereKey($reservasi->jadwalId)
						->lockForUpdate()
						->firstOrFail();

					$reservasi->update([
						'status' => 'paid',
					]);

					$jadwal = $jadwal->syncQuotaFromActiveReservations();

					$jadwalDiperbarui = true;
					if ($jadwal) {
						event(JadwalKuotaUpdated::fromJadwal($jadwal));
					}
				} elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'refund', 'partial_refund', 'chargeback'], true)) {
					$reservasi->update([
						'status' => $reservasiStatus,
					]);

					$jadwal = Jadwal::whereKey($reservasi->jadwalId)
						->lockForUpdate()
						->firstOrFail();

					$jadwal = $jadwal->syncQuotaFromActiveReservations();
					event(JadwalKuotaUpdated::fromJadwal($jadwal));
					$jadwalDiperbarui = true;
				} else {
					$reservasi->update([
						'status' => $reservasiStatus,
					]);
				}
			}

			return $pembayaran->fresh(['reservasi.jadwal.paketTrip']);
		});
	}

	private function configure(): void
	{
		Config::$serverKey = (string) config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
		Config::$clientKey = (string) config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY'));
		Config::$isProduction = filter_var(config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false)), FILTER_VALIDATE_BOOL);
		Config::$isSanitized = true;
		Config::$is3ds = true;
	}
}
