<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class TransaksiPengiriman extends Model
{
    protected $table = 'transaksi_pengiriman';

    protected $fillable = [
        'id_transaksi_penjualan',
        'id_pegawai',
        'tgl_pengiriman',
        'status_pengiriman',
        'biaya_pengiriman',
        'catatan',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_transaksi_penjualan');
    }

    public function transaksiPenjualan(): BelongsTo
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi_penjualan');
    }


    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

}
