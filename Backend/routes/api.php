<?php

Use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\AlamatPembeli;
use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\DetailPengirimian;
use App\Models\DetaiTransaksiPenjualan;
use App\Models\Jabatan;
use App\Models\KategoriBarang;
use App\Models\Merchandise;
use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\PenukaranMerchandise;
use App\Models\TransaksiDonasi;
use App\Models\TransaksiPenitipan;
use App\Models\TransaksiPenjualan;

// route('api')->middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
