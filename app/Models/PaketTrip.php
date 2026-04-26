<?php

namespace App\Models;

use App\Enums\PaketTripKategori;
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

    public function reservasis()
    {
        return $this->hasManyThrough(Reservasi::class, Jadwal::class, 'paketId', 'jadwalId', 'paketId', 'jadwalId');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->foto);
    }

    public function getFoto2UrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->foto2);
    }

    public function getFoto3UrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->foto3);
    }

    public function getFoto4UrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->foto4);
    }

    public function getKategoriLabelAttribute(): ?string
    {
        return PaketTripKategori::labelFor($this->kategori);
    }

    public function getKategoriValueAttribute(): ?string
    {
        return PaketTripKategori::valueFor($this->kategori);
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
