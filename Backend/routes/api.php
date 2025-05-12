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
=======
//Pegawai
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
Route::get('/jabatan/{id}', [JabatanController::class, 'show']);
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


//Penitip
Route::get('/penitip', [PenitipController::class, 'index']);
Route::post('/penitip', [PenitipController::class, 'store']);
Route::get('/penitip/{id}', [PenitipController::class, 'show']);
Route::put('/penitip/{id}', [PenitipController::class, 'update']);
Route::delete('/penitip/{id}', [PenitipController::class, 'destroy']);
Route::get('/penitip/search/{name}', [PenitipController::class, 'searchByName']);
Route::get('/penitip/{id}', [PenitipController::class, 'searchById']);

//Organisasi
Route::get('/organisasi', [OrganisasiController::class, 'index']);
Route::post('/organisasi', [OrganisasiController::class, 'store']);
Route::get('/organisasi/{id}', [OrganisasiController::class, 'show']);
Route::put('/organisasi/{id}', [OrganisasiController::class, 'update']);
Route::delete('/organisasi/{id}', [OrganisasiController::class, 'destroy']);
Route::get('/organisasi/search/{name}', [OrganisasiController::class, 'searchByName']);
Route::get('/organisasi/{id}', [OrganisasiController::class, 'searchById']);
Route::get('/organisasi/searchByPermintaan/{permintaan}', [OrganisasiController::class, 'searchByPermintaan']);
