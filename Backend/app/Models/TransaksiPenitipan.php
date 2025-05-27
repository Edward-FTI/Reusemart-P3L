<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPenitipan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'tgl_penitipan',
        'status_penitipan',
        'durasi_penitipan',
        'batas_akhir',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
