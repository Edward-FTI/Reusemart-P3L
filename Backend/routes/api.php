<?php

use App\Http\Controllers\Api\JabatanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PegawaiController;
use APP\Http\Controllers\Api\PembeliController;
use App\Http\Controllers\Api\OrganisasiController;
use App\Http\Controllers\Api\PenitipController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);




Route::get('/pegawai', [PegawaiController::class, 'index']);
Route::post('/pegawai', [PegawaiController::class, 'store']);
Route::get('/pegawai/{id}', [PegawaiController::class, 'show']);
Route::put('/pegawai/{id}', [PegawaiController::class, 'update']);
Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);
Route::get('/pegawai/search/{name}', [PegawaiController::class, 'searchByName']);
Route::get('/searchByJabatan/{jabatan}', [PegawaiController::class, 'searchBysearchByJabatanyName']);
Route::get('/pegawai/{id}', [PegawaiController::class, 'searchById']);

Route::get('/jabatan', [JabatanController::class, 'index']);
Route::put('/pegawai/reset-password/{id}', [PegawaiController::class, 'resetPassword']);
