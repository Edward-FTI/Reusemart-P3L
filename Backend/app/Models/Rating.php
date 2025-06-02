<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'id_pembeli',
        'id_detail_transaksi',
        'id_transaksi_penjualan',
        'id_penitip',
        'nilai_rating',
    ];

    public function pembeli(): BelongsTo {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    public function transaksi(): BelongsTo {
        return $this->belongsTo(Detail_transaksi_penjualan::class, 'id_detail_transaksi');
    }
    public function penitip(): BelongsTo {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
