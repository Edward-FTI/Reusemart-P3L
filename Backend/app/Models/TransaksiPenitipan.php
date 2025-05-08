<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenitipan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_penitipan',
        'tgl_penitipan',
        'status_penitipan',
        'durasi_penitipan',
        'batas_akhir',
    ];
}
