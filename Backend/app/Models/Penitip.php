<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penitip',
        'no_ktp',
        'alamat',
        'gambar_ktp',
        'saldo',
        'point',
        'email',
        'password',
        'badge',
        'nominalTarik',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

}
