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

	public function createSnapToken(Reservasi $reservasi, ?string $orderId = null): ?string
	{
		try {
			$this->configure();

			$payload = [
				'transaction_details' => [
					'order_id' => $orderId ?? ('ORD-' . $reservasi->kode_reservasi),
					'gross_amount' => (int) round($reservasi->total_harga),
				],

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
				'notification_url' => route('midtrans.notification'),
				'callbacks' => [
					'finish' => route('reservasi.status', $reservasi->kode_reservasi),
				],
                
			];

			return Snap::getSnapToken($payload);
		} catch (\Throwable $throwable) {
			Log::warning('Gagal membuat Snap token reservasi.', [
				'reservasiId' => $reservasi->reservasiId,
				'kode_reservasi' => $reservasi->kode_reservasi,
				'message' => $throwable->getMessage(),
			]);

			return null;
		}
	}

	public function handleNotification(): ?Pembayaran
	{
		try {
			$this->configure();

			$notification = new Notification();
			$orderId = $notification->order_id ?? $notification->transaction_id ?? null;

			if (! $orderId) {
				return null;
			}

			$pembayaran = Pembayaran::with('reservasi')->where('orderId', $orderId)->first();

			if (! $pembayaran) {
				return null;
			}

			return $this->applyGatewayStatus($pembayaran, $notification);
		} catch (\Throwable $throwable) {
			Log::error('Midtrans notification gagal diproses.', [
				'message' => $throwable->getMessage(),
			]);

			return null;
		}
	}

	public function refreshPaymentStatus(Reservasi $reservasi): Pembayaran
	{
		$reservasi->loadMissing(['jadwal.paketTrip', 'user', 'pembayaran']);

		$pembayaran = $reservasi->pembayaran;

		if (! $pembayaran || empty($pembayaran->snap_token)) {
			$pembayaran = $this->syncPayment($reservasi);
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

	private function applyGatewayStatus(Pembayaran $pembayaran, mixed $gatewayResponse): Pembayaran
	{
		return DB::transaction(function () use ($pembayaran, $gatewayResponse) {
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

				if (in_array($transactionStatus, ['settlement', 'capture'], true) && $reservasi->status !== 'paid') {
					$jadwal = Jadwal::whereKey($reservasi->jadwalId)
						->lockForUpdate()
						->firstOrFail();

					$reservasi->update([
						'status' => 'paid',
					]);

					$jadwal = $jadwal->syncQuotaFromPaidReservations();

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

					$jadwal = $jadwal->syncQuotaFromPaidReservations();
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
