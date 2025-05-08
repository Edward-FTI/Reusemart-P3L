<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi dinamis ke model berdasarkan role
    public function profile()
    {
        return match ($this->role) {
            'pegawai' => $this->belongsTo(Pegawai::class, 'profile_id'),
            'organisasi' => $this->belongsTo(Organisasi::class, 'profile_id'),
            'penitip' => $this->belongsTo(Penitip::class, 'profile_id'),
            'pembeli' => $this->belongsTo(Pembeli::class, 'profile_id'),
            default => null,
        };
    }
}
