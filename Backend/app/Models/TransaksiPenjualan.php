<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPengiriman;
use App\Models\Pembeli;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pengiriman',
        'id_pembeli',
        'total_harga_pembelian',
        'alamat_pengiriman',
        'ongkir',
        'bukti_pembayaran',
    ];

    public function detailPengiriman(): BelongsTo
    {
        return $this->belongsTo(DetailPengiriman::class, 'id_pengiriman');
    }
    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
