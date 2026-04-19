<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';
    protected $primaryKey = 'jadwalId';
    protected $fillable = [
        'paketId',
        'tanggal_berangkat',
        'tanggal_kembali',
        'kuota_max',
        'kuota_terisi',
        'status',
        'harga_override',
        'cutoff_booking'
    ];

    public function paketTrip()
    {
        return $this->belongsTo(PaketTrip::class, 'paketId');
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'jadwalId');
    }

    public function syncQuotaFromPaidReservations(): self
    {
        $kuotaTerisi = (int) $this->reservasis()
            ->where('status', 'paid')
            ->sum('jml_peserta');

        $status = $this->status === 'cancelled'
            ? 'cancelled'
            : ($kuotaTerisi >= (int) $this->kuota_max ? 'full' : 'open');

        $this->update([
            'kuota_terisi' => $kuotaTerisi,
            'status' => $status,
        ]);

        return $this->fresh(['paketTrip']);
    }
}
