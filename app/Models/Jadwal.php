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
}
