<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatPembeli extends Model
{
    use HasFactory;

    protected $fillabe = [
        'id_pembeli',
        'alamat',
    ];
}
