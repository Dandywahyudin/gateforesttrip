<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $primaryKey = 'pembayaranId';
    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];
    protected $fillable = [
        'reservasiId',
        'orderId',
        'metode_pembayaran',
        'jumlah',
        'status',
        'paid_at',
        'expired_at',
        'snap_token'
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasiId');
    }
}
