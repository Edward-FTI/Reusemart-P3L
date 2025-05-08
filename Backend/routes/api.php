<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\Api\PegawaiController;

use App\Models\Pegawai;

Route::get('/pegawai', [PegawaiController::class, 'index']);

