<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function beliBarang(): BelongsTo {
        return $this->belongsTo(Barang::class);
    }

    public function carts()
{
    return $this->hasMany(Cart::class, 'id_pembeli');
}

}
