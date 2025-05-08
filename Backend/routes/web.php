<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\PegawaiController;

Route::get('/', function () {
    return view('welcome');
});


// use App\Http\Controllers\Api\PegawaiController;

// Route::get('/pegawai', [PegawaiController::class, 'index']);
// Route::post('/pegawai', [PegawaiController::class, 'store']);
// Route::put('/pegawai/{id}', [PegawaiController::class, 'update']);
// Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);

