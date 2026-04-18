<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasis';
    protected $primaryKey = 'reservasiId';
    protected $fillable = [
        'userId',
        'jadwalId',
        'kode_reservasi',
        'catatan',
        'jml_peserta',
        'total_harga',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwalId');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'reservasiId');
    }

    public function peserta()
    {
        return $this->hasMany(PesertaTrip::class, 'reservasiId');
    }

    public function getRouteKeyName(): string
    {
        return 'kode_reservasi';
    }
}
