<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'alamat',
        'permintaan',
        'email',
        'password',
        'no_hp',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'profile_id')->where('role', 'organisasi');
    }
}
