<?php

use App\Http\Controllers\Api\JabatanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KategoriBarangController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PembeliController;
use App\Http\Controllers\Api\OrganisasiController;
use App\Http\Controllers\Api\PenitipController;
use App\Http\Controllers\Api\AlamatPembeliController;

// Route ambil user yang login
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk login dan register (TIDAK butuh auth)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [PembeliController::class, 'register']);
Route::post('/register-org', [OrganisasiController::class, 'registerOrg']);

// Semua route di bawah HANYA bisa diakses jika sudah login
Route::middleware('auth:api')->group(function () {

    // ======================= Pegawai =======================
    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai', [PegawaiController::class, 'store']);
    Route::get('/pegawai/{id}', [PegawaiController::class, 'show']);
    Route::put('/pegawai/{id}', [PegawaiController::class, 'update']);
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);
    Route::get('/pegawai/search/{name}', [PegawaiController::class, 'searchByName']);
    Route::get('/searchByJabatan/{jabatan}', [PegawaiController::class, 'searchBysearchByJabatanyName']);
    Route::put('/pegawai/reset-password/{id}', [PegawaiController::class, 'resetPassword']);

    // ======================= Jabatan =======================
    Route::get('/jabatan', [JabatanController::class, 'index']);
    Route::post('/jabatan', [JabatanController::class, 'store']);
    Route::get('/jabatan/{id}', [JabatanController::class, 'show']);

    // ======================= Barang =======================
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    // ======================= Kategori Barang =======================
    Route::get('/kategori', [KategoriBarangController::class, 'index']);

    // ======================= Penitip =======================
    Route::get('/penitip', [PenitipController::class, 'index']);
    Route::post('/penitip', [PenitipController::class, 'store']);
    Route::get('/penitip/{id}', [PenitipController::class, 'show']);
    Route::put('/penitip/{id}', [PenitipController::class, 'update']);
    Route::delete('/penitip/{id}', [PenitipController::class, 'destroy']);
    Route::get('/penitip/search/{name}', [PenitipController::class, 'searchByName']);

    // ======================= Organisasi =======================
    Route::get('/organisasi', [OrganisasiController::class, 'index']);
    Route::get('/organisasi/{id}', [OrganisasiController::class, 'show']);
    Route::put('/organisasi/{id}', [OrganisasiController::class, 'update']);
    Route::delete('/organisasi/{id}', [OrganisasiController::class, 'destroy']);
    Route::get('/organisasi/search/{name}', [OrganisasiController::class, 'searchByName']);
    Route::get('/organisasi/searchByPermintaan/{permintaan}', [OrganisasiController::class, 'searchByPermintaan']);

    // ======================= Alamat Pembeli =======================
    Route::get('/alamat', [AlamatPembeliController::class, 'index']);
    Route::get('/alamat/{id}', [AlamatPembeliController::class, 'show']);
    Route::post('/alamat', [AlamatPembeliController::class, 'store']);
    Route::put('/alamat/{id}', [AlamatPembeliController::class, 'update']);
    Route::delete('/alamat/{id}', [AlamatPembeliController::class, 'destroy']);

    // ======================= Pembeli =======================
    Route::get('/pembeli', [PembeliController::class, 'index']);
    Route::post('/pembeli', [PembeliController::class, 'store']);
    Route::get('/pembeli/{id}', [PembeliController::class, 'show']);
    Route::put('/pembeli/{id}', [PembeliController::class, 'update']);
    Route::delete('/pembeli/{id}', [PembeliController::class, 'destroy']);
    Route::get('/pembeli/search/{name}', [PembeliController::class, 'searchByName']);
    Route::get('/pembeli/searchByEmail/{email}', [PembeliController::class, 'searchByEmail']);
});
