<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPengiriman extends Model
{
    protected $table = 'transaksi_pengiriman';

    protected $fillable = [
        'id_barang',
        'id_pegawai',
        'tgl_pengiriman',
        'status_pengiriman',
        'alamat_pengiriman',
        'biaya_pengiriman',
        'catatan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
