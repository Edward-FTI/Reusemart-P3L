<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDonasi extends Model
{
    use HasFactory;

    protected $table = 'transaksi_donasis';

    protected $fillable = [
        'id_organisasi',
        'id_penitip',
        'id_barang',
        'nama_penerima',
        'status',
        'tgl_transaksi',  // ← ini cukup nama kolomnya saja
    ];

    // Casting untuk tgl_transaksi agar otomatis jadi objek Carbon (tanggal)
    protected $casts = [
        'tgl_transaksi' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
}

