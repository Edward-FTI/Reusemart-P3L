<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenukaranMerchandise extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pembeli',
        'id_merchandise',
        'id_pegawai',
        'tanggal_penukaran',
        'status',
        'jumlah',
    ];

    public function pembeli(): Belongsto
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    public function merchandise(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class, 'id_merchandise');
    }
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

}
