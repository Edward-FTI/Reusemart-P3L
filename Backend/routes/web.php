<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\PegawaiController;

Route::get('/', function () {
    return view('welcome');
});

// // GET: Ambil semua pegawai
// Route::get('/pegawai', [PegawaiController::class, 'index']);

// // POST: Tambah pegawai baru
// Route::post('/pegawai', [PegawaiController::class, 'store']);


// // PUT/PATCH: Update data pegawai berdasarkan ID
// Route::put('/pegawai/{id}', [PegawaiController::class, 'update']);
// Route::patch('/pegawai/{id}', [PegawaiController::class, 'update']);

// // DELETE: Hapus data pegawai berdasarkan ID
// Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);
