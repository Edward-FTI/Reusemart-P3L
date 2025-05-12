<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengiriman extends Model
{
    protected $table = 'detail_pengirimans'; // <- Pastikan ini benar

    protected $fillable = [
        'status_pengiriman',
        'metode_pengiriman',
    ];
}
