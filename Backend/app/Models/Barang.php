<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_penitip',
        'id_kategori',
        'id_pegawai',
        'id_hunter',
        'tgl_penitipan',
        'masa_penitipan',
        'penambahan_durasi',
        'tgl_pengambilan',
        'nama_barang',
        'harga_barang',
        'berat_barang',
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
    public function pegawai(): BelongsTo {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
    public function hunter(): BelongsTo {
        return $this->belongsTo(Pegawai::class, 'id_hunter');
    }
}
