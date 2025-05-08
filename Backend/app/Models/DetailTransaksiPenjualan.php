<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaksi_penjualan',
        'id_pegawai',
        'status_pembelian',
        'verifikasi_pembayaran',
    ];
}
