<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\JabatanController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PembeliController;
use App\Http\Controllers\Api\PenitipController;
use App\Http\Controllers\Api\OrganisasiController;
use App\Http\Controllers\Api\AlamatPembeliController;
use App\Http\Controllers\Api\KategoriBarangController;
use App\Http\Controllers\Api\TransaksiDonasiController;
use App\Http\Controllers\Api\DetailPengirimanController;
use App\Http\Controllers\Api\TransaksiPenjualanController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk login dan register (TIDAK butuh auth)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [PembeliController::class, 'register']);
Route::post('/register-org', [OrganisasiController::class, 'registerOrg']);


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
    Route::get('/pegawai/{id}', [PegawaiController::class, 'searchById']);
    Route::put('/pegawai/reset-password/{id}', [PegawaiController::class, 'resetPassword']);

    // ======================= Jabatan =======================
    Route::get('/jabatan', [JabatanController::class, 'index']);
    Route::post('/jabatan', [JabatanController::class, 'store']);
    Route::get('/jabatan/{id}', [JabatanController::class, 'show']);

    // ======================= Barang =======================
    Route::get('/barang-qc', [BarangController::class, 'index']);
    Route::post('/barang-qc', [BarangController::class, 'store']);
    Route::get('/barang-qc/{id}', [BarangController::class, 'show']);
    Route::put('/barang-qc/{id}', [BarangController::class, 'update']);
    Route::delete('/barang-qc/{id}', [BarangController::class, 'destroy']);


    // ======================= Kategori Barang =======================
    Route::get('/kategori', [KategoriBarangController::class, 'index']);

    // ======================= Penitip =======================
    Route::get('/penitip', [PenitipController::class, 'index']);
    Route::post('/penitip', [PenitipController::class, 'store']);
    Route::get('/penitip/{id}', [PenitipController::class, 'show']);
    Route::put('/penitip/{id}', [PenitipController::class, 'update']);
    Route::delete('/penitip/{id}', [PenitipController::class, 'destroy']);
    Route::get('/penitip/search/{name}', [PenitipController::class, 'searchByName']);
    Route::get('/penitip/{id}', [PenitipController::class, 'searchById']);

    // ======================= Organisasi =======================
    Route::get('/organisasi', [OrganisasiController::class, 'index']);
    Route::get('/organisasi/{id}', [OrganisasiController::class, 'show']);
    Route::put('/organisasi/{id}', [OrganisasiController::class, 'update']);
    Route::delete('/organisasi/{id}', [OrganisasiController::class, 'destroy']);
    Route::get('/organisasi/search/{name}', [OrganisasiController::class, 'searchByName']);
    Route::get('/organisasi/searchByPermintaan/{permintaan}', [OrganisasiController::class, 'searchByPermintaan']);
    Route::get('/organisasi/{id}', [OrganisasiController::class, 'searchById']);
    Route::get('/organisasi/searchById/{id}', [OrganisasiController::class, 'searchById']);
    Route::get('/ujiankelas', [OrganisasiController::class, 'showPermintaan']);


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
    Route::get('/pembeli/{id}', [PembeliController::class, 'searchById']);
    // Ganti route-nya menjadi tanpa parameter ID
    Route::post('/pembeli/reset-password', [PembeliController::class, 'resetPassword']);


    //Detail Pengiriman
    Route::get('/detail-pengiriman', [DetailPengirimanController::class, 'index']);
    Route::post('/detail-pengiriman', [DetailPengirimanController::class, 'store']);
    Route::get('/detail-pengiriman/{id}', [DetailPengirimanController::class, 'show']);
    Route::put('/detail-pengiriman/{id}', [DetailPengirimanController::class, 'update']);
    Route::delete('/detail-pengiriman/{id}', [DetailPengirimanController::class, 'destroy']);

    //Transaksi Penjualan
    Route::get('/transaksi-penjualan', [TransaksiPenjualanController::class, 'index']);
    Route::post('/transaksi-penjualan', [TransaksiPenjualanController::class, 'store']);
    Route::get('/transaksi-penjualan/{id}', [TransaksiPenjualanController::class, 'show']);
    Route::put('/transaksi-penjualan/{id}', [TransaksiPenjualanController::class, 'update']);
    Route::delete('/transaksi-penjualan/{id}', [TransaksiPenjualanController::class, 'destroy']);
    Route::get('/transaksi-penjualan/searchByIdPembeli/{id}', [TransaksiPenjualanController::class, 'searchByIdPembeli']);
    Route::get('/transaksi-penjualan/getPembeli', [TransaksiPenjualanController::class, 'getPembeli']);
    Route::get('/transaksi-penjualan/getDetailPengiriman', [TransaksiPenjualanController::class, 'getDetailPengiriman']);
    Route::get('/transaksi-penjualan/searchById/{id}', [TransaksiPenjualanController::class, 'searchById']);

    //Transaksi Donasi
    Route::get('/transaksi-donasi', [TransaksiDonasiController::class, 'index']);
    Route::post('/transaksi-donasi', [TransaksiDonasiController::class, 'store']);
    Route::get('/transaksi-donasi/{id}', [TransaksiDonasiController::class, 'show']);
    Route::put('/transaksi-donasi/{id}', [TransaksiDonasiController::class, 'update']);
    Route::delete('/transaksi-donasi/{id}', [TransaksiDonasiController::class, 'destroy']);
    Route::get('/transaksi-donasi/searchByIdOrganisasi/{id}', [TransaksiDonasiController::class, 'searchByIdOrganisasi']);
});

Route::get('/barang', [BarangController::class, 'indexPublic']);
// Route::post('/barang', [BarangController::class, 'store']);
Route::get('/barang/{id}', [BarangController::class, 'showPublic']);
// Route::put('/barang/{id}', [BarangController::class, 'update']);
// Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

//Organisasi
Route::get('/organisasi', [OrganisasiController::class, 'index']);
Route::post('/organisasi', [OrganisasiController::class, 'store']);
Route::get('/organisasi/{id}', [OrganisasiController::class, 'show']);
Route::put('/organisasi/{id}', [OrganisasiController::class, 'update']);
Route::delete('/organisasi/{id}', [OrganisasiController::class, 'destroy']);
Route::get('/organisasi/search/{name}', [OrganisasiController::class, 'searchByName']);
Route::get('/organisasi/{id}', [OrganisasiController::class, 'searchById']);
Route::get('/organisasi/searchByPermintaan/{permintaan}', [OrganisasiController::class, 'searchByPermintaan']);


// donasi
Route::get('/donasi', [TransaksiDonasiController::class, 'index']);
Route::put('/donasi/{id}', [TransaksiDonasiController::class, 'update']);
