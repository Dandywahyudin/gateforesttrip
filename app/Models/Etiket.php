<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etiket extends Model
{
    protected $table = 'etikets';
    protected $primaryKey = 'tiketId';
    protected $fillable = [
        'jadwalId',
        'nama',
        'harga',
        'kuota'
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasiId');
    }
}
