<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    use HasFactory;

    protected $fillabel = [
        'nama',
        'alamat',
        'permintaan',
        'email',
        'password',
        'no_hp',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

}
