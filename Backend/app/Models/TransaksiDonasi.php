<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiDonasi extends Model
{
    protected $table = 'transaksi_donasis';
    use HasFactory;

    protected $fillable = [
        'id_organisasi',
        'status',
        'nama_penitip',
        'jenis_barang',
        'jumlah_barang',
        'tgl_transaksi',
    ];

    public function organisasi(): BelongsTo {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
}
