<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organisasi extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = 'organisasis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'alamat', 
        'permintaan', 
        'email',
        'password',
        'no_hp',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

        public function transaksi_donasi(): BelongsTo {
        return $this->belongsTo(TransaksiDonasi::class, 'id_organisasi');
    }
   
}
