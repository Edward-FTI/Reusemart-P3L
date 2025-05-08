<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'status_barang',
        'jumlah_barang'
    ];
}
