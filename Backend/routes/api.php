<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PegawaiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::get('/pegawai', [PegawaiController::class, 'index']);
Route::post('/pegawai', [PegawaiController::class, 'store']);
Route::get('/pegawai/{id}', [PegawaiController::class, 'show']);
Route::put('/pegawai/{id}', [PegawaiController::class, 'update']);
Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);
Route::get('/pegawai/search/{name}', [PegawaiController::class, 'searchByName']);
Route::get('/searchByJabatan/{jabatan}', [PegawaiController::class, 'searchBsearchByJabatanyName']);
Route::get('/pegawai/{id}', [PegawaiController::class, 'searchById']);
