<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nama_penitip',
        'no_ktp',
        'saldo',
        'point',
        'email',
        'password',
        'badge',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'profile_id')->where('role', 'penitip');
    }
}
