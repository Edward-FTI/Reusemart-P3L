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


    public function barangs()
    {
        return $this->hasManyThrough(
            Barang::class,
            Detail_transaksi_penjualan::class,
            'id_transaksi_penjualan', // foreign key di detail transaksi
            'id',                      // foreign key di barang
            'id_transaksi_penjualan', // local key di TransaksiPengiriman
            'id_barang'               // local key di detail transaksi
        );
    }

    // relasi ke pembeli lewat transaksi
    public function pembeli()
    {
        return $this->hasOneThrough(
            Pembeli::class,
            TransaksiPenjualan::class,
            'id', // id transaksi_penjualan
            'id', // id pembeli
            'id_transaksi_penjualan', // foreign key di TransaksiPengiriman
            'id_pembeli' // foreign key di TransaksiPenjualan
        );
    }

}
