<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_penitip',
        'id_kategori',
        'tgl_penitipan',
        'nama_barang',
        'harga_barang',
        'deskripsi',
        'status_garansi',
        'status_barang',
        'gambar',
        'gambar_dua',
    ];

    public function penitip(): BelongsTo {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
    public function kategori_barang(): BelongsTo {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }
}
