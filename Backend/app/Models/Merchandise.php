<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchandise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_merchandise',
        'jumlah',
        'gambar',
        'nilai_point',
    ];

    public function penukaranMerchandise(): HasMany
    {
        return $this->hasMany(PenukaranMerchandise::class, 'id_merchandise');
    }
}
