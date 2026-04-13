<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketTrip extends Model
{
    protected $table = 'paket_trips';
    protected $primaryKey = 'paketId';
    protected $fillable = [
        'nama',
        'deskripsi',
        'fasilitas',
        'lokasi',
        'kategori',
        'meeting_point',
        'include',
        'exclude',
        'durasi_hari',
        'harga',
        'foto',
        'foto2',
        'foto3',
        'foto4',
        'slug',
        'aktif'
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'paketId');
    }
}
