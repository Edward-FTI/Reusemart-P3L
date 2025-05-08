<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id_jabatan',
        'nama',
        'email',
        'password',
        'gaji',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'profile_id')->where('role', 'pegawai');
    }
}
