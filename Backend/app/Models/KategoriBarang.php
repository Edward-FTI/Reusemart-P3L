<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
    ];

    // public function barang(): HasMany
    // {
    //     return $this->hasMany(KategoriBarang::class);
    // }

    public function barangId(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_kategori');
    }
}
