<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenukaranMerchandise extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pembeli',
        'id_merchandise',
        'id_pegawai',
        'tanggal_penukaran',
        'status',
        'jumlah',
    ];
}
