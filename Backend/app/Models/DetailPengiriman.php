<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengirimian extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_pengiriman',
        'metode_pengiriman',
    ];
}
