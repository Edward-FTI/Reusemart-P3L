<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembeli extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = 'pembelis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_pembeli',  
        'email',
        'password',
        'no_hp',
        'point',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
