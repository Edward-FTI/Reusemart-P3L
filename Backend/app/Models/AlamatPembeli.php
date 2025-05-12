<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatPembeli extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'alamat_pembelis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_pembeli',
        'alamat',
    ];
}
