<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDonasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_organisasi',
        'nama_penitip',
        'tgl_transaksi',
    ];
}
