<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPengiriman;
use App\Models\Detail_transaksi_penjualan;
// use App\Models\DetailTransaksiPenjualan;
use App\Models\Pembeli;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pembeli',
        'total_harga_pembelian',
        'metode_pengiriman',
        'alamat_pengiriman',
        'ongkir',
        'bukti_pembayaran',
        'status_pengiriman',
        'id_pegawai',
        'status_pembelian',
        'verifikasi_pembayaran',
    ];

    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    public function detailTransaksi()
    {
        return $this->hasMany(Detail_transaksi_penjualan::class, 'id_transaksi_penjualan');
    }
    public function detail()
    {
        return $this->hasMany(Detail_transaksi_penjualan::class, 'id_transaksi_penjualan');
    }

}
