<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_jabatan',
        'nama',
        'email',
        'password',
        'gaji',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

    public function jabatan(): BelongsTo {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }
}
