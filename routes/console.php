<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Events\JadwalKuotaUpdated;
use App\Models\Jadwal;
use App\Models\Pembayaran;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reservasi:sync-kuota', function () {
    $expiredPembayarans = Pembayaran::with(['reservasi.jadwal.paketTrip'])
        ->where('status', 'pending')
        ->whereNotNull('expired_at')
        ->where('expired_at', '<=', now())
        ->get();

    $affectedJadwalIds = [];

    foreach ($expiredPembayarans as $pembayaran) {
        DB::transaction(function () use ($pembayaran, &$affectedJadwalIds) {

            $pembayaran = Pembayaran::with(['reservasi.jadwal.paketTrip'])
                ->lockForUpdate()
                ->find($pembayaran->pembayaranId);

            if (! $pembayaran || ! $pembayaran->reservasi) {
                return;
            }

            if ($pembayaran->reservasi->status === 'paid') {
                return;
            }

            $reservasi = $pembayaran->reservasi;

            $pembayaran->update([
                'status' => 'expire',
            ]);

            $reservasi->update([
                'status' => 'cancelled',
            ]);

            $jadwal = Jadwal::whereKey($reservasi->jadwalId)
                ->lockForUpdate()
                ->first();

            if ($jadwal) {
                $affectedJadwalIds[] = $jadwal->jadwalId;
            }
        });
    }

    $jadwals = Jadwal::with('paketTrip')
        ->whereIn('jadwalId', array_values(array_unique($affectedJadwalIds)))
        ->get();

    foreach ($jadwals as $jadwal) {
        $jadwal = $jadwal->syncQuotaFromActiveReservations();
        event(JadwalKuotaUpdated::fromJadwal($jadwal));
    }

    $this->info(sprintf('Expired %d payment(s) and synchronized %d schedule(s).', $expiredPembayarans->count(), $jadwals->count()));
})->purpose('Expire unpaid reservations and sync jadwal quota from paid reservations');

Schedule::command('reservasi:sync-kuota')->everyMinute();
