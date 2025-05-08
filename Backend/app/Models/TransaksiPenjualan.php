<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
