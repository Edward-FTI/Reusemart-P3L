<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nama_pembeli',
        'email',
        'password',
        'no_hp',
        'point',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'profile_id')->where('role', 'pembeli');
    }
}
