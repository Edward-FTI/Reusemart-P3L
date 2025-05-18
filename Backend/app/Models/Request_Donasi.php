<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request_Donasi extends Model
{
    protected $table = 'request__donasis';
    protected $fillable = [
        'id_organisasi',
        'request',
    ];

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
}
