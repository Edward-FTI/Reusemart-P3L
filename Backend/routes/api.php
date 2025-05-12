<?php

use App\Http\Controllers\Api\JabatanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KategoriBarangController;
use App\Http\Controllers\Api\PegawaiController;
use APP\Http\Controllers\Api\PembeliController;
use App\Http\Controllers\Api\OrganisasiController;
use App\Http\Controllers\Api\PenitipController;
use App\Models\Barang;
use App\Models\KategoriBarang;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);



// Punya Abang Edward
//======================= Untuk Pegawai =======================
Route::get('/pegawai', [PegawaiController::class, 'index']);
Route::post('/pegawai', [PegawaiController::class, 'store']);
Route::get('/pegawai/{id}', [PegawaiController::class, 'show']);
Route::put('/pegawai/{id}', [PegawaiController::class, 'update']);
Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);
Route::get('/pegawai/search/{name}', [PegawaiController::class, 'searchByName']);
Route::get('/searchByJabatan/{jabatan}', [PegawaiController::class, 'searchBysearchByJabatanyName']);
Route::get('/pegawai/{id}', [PegawaiController::class, 'searchById']);
// =========================================================================================================

//======================= Untuk Jabatan =======================
Route::get('/jabatan', [JabatanController::class, 'index']);
Route::post('/jabatan', [JabatanController::class, 'store']);
// =========================================================================================================

//======================= Untuk Barang =======================
Route::get('/barang', [BarangController::class, 'index']);
Route::post('/barang', [BarangController::class, 'store']);
Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::put('/barang/{id}', [BarangController::class, 'update']);
Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
// =========================================================================================================

//======================= Untuk Kategori Barang =======================
Route::get('/kategori', [KategoriBarangController::class, 'index']);
// =========================================================================================================








