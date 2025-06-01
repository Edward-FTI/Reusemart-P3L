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

    /**
     * Get the Barang (item) associated with the TransaksiPengiriman.
     * Note: Based on your schema, it seems 'id_transaksi_penjualan' is used here,
     * which might be unusual if a single pengiriman relates to a single barang directly.
     * If a pengiriman is for a whole transaction, then 'id_transaksi_penjualan'
     * should link to TransaksiPenjualan, and Barang would be accessed via Detail_transaksi_penjualan.
     * I'm keeping it as per your original model, but highlighting this potential point.
     */
    public function barang(): BelongsTo
    {
        // If 'id_transaksi_penjualan' is indeed a foreign key to 'barang' table's ID,
        // then this relationship is correct. However, if it's a foreign key to TransaksiPenjualan,
        // then this relationship might be incorrect for direct barang access.
        return $this->belongsTo(Barang::class, 'id_transaksi_penjualan');
    }

    /**
     * Get the TransaksiPenjualan that this TransaksiPengiriman belongs to.
     */
    public function transaksiPenjualan(): BelongsTo
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi_penjualan');
    }

    /**
     * Get the Pegawai (employee) who handled this TransaksiPengiriman.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    // You might also need a relationship to Pembeli if directly linked,
    // but based on your controller's 'with' clause, Pembeli is accessed via TransaksiPenjualan.
    // public function pembeli(): BelongsTo
    // {
    //     return $this->belongsTo(Pembeli::class, 'id_pembeli'); // Assuming 'id_pembeli' exists in transaksi_pengiriman table
    // }
}
