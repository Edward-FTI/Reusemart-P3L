<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pembeli extends Model
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
