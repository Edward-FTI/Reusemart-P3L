<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request_Donasi extends Model
{
    protected $table = 'request_donasis';
    protected $fillable = [
        'id_organisasi',
        'request',
        'status',
    ];

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }
}
