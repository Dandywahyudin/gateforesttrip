<?php

namespace App\Events;

use App\Models\Jadwal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class JadwalKuotaUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $jadwalId,
        public array $payload,
    ) {
    }

    public static function fromJadwal(Jadwal $jadwal, bool $deleted = false): self
    {
        $jadwal->loadMissing(['paketTrip', 'reservasis.pembayaran']);

        $harga = $jadwal->harga_override ?? $jadwal->paketTrip?->harga ?? 0;
        $sisaKuota = max(0, (int) $jadwal->kuota_max - (int) $jadwal->kuota_terisi);

        return new self($jadwal->jadwalId, [
            'jadwal_id' => $jadwal->jadwalId,
            'paket_id' => $jadwal->paketId,
            'paket_nama' => $jadwal->paketTrip?->nama,
            'tanggal_berangkat_label' => Carbon::parse($jadwal->tanggal_berangkat)->translatedFormat('d M Y'),
            'tanggal_kembali_label' => Carbon::parse($jadwal->tanggal_kembali)->translatedFormat('d M Y'),
            'tanggal_berangkat_raw' => Carbon::parse($jadwal->tanggal_berangkat)->toDateString(),
            'tanggal_kembali_raw' => Carbon::parse($jadwal->tanggal_kembali)->toDateString(),
            'kuota_max' => (int) $jadwal->kuota_max,
            'kuota_terisi' => (int) $jadwal->kuota_terisi,
            'sisa_kuota' => $sisaKuota,
            'status' => $jadwal->status,
            'harga_label' => 'Rp ' . number_format((float) $harga, 0, ',', '.'),
            'harga' => (float) $harga,
            'deleted' => $deleted,
        ]);
    }

    public function broadcastOn(): array
    {
        return [new Channel('jadwal.' . $this->jadwalId)];
    }

    public function broadcastAs(): string
    {
        return 'jadwal.kuota.updated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}