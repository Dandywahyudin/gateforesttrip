<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaTrip extends Model
{
    protected $table = 'peserta_trips';
    protected $primaryKey = 'pesertaId';
    protected $fillable = [
        'reservasiId',
        'nama',
        'email',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasiId');
    }
}
