<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use SoftDeletes;
    
    protected $table = 'pembayarans';
    protected $primaryKey = 'pembayaranId';
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

    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasiId');
    }
}
