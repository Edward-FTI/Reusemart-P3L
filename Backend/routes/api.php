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
use App\Http\Controllers\api\OwnerController;
use App\Http\Controllers\Api\TransaksiPenjualanController;
use App\Http\Controllers\Api\TransaksiPenitipanController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\PengambilanController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RequestDonasiController;
use App\Http\Controllers\Api\TransaksiPengirimanController;
use App\Http\Controllers\Api\MerchandiseController;
use App\Http\Controllers\Api\PenukaranMerchandiseController;
use App\Models\Rating;
use App\Models\TransaksiDonasi;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk login dan register (TIDAK butuh auth)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [PembeliController::class, 'register']);
Route::post('/register-org', [OrganisasiController::class, 'registerOrg']);


Route::middleware('auth:api')->group(function () {


    // routeFCM token
    Route::put('/update-fcm-token/{id}', [AuthController::class, 'updateFcmToken']);

    // ======================= Pegawai =======================
    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai', [PegawaiController::class, 'store']);
    Route::get('/pegawai/{id}', [PegawaiController::class, 'show']);
    Route::put('/pegawai/{id}', [PegawaiController::class, 'update']);
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy']);
    Route::get('/pegawaiid', [PegawaiController::class, 'getPegawaiData']);
    Route::get('/pegawai/search/{name}', [PegawaiController::class, 'searchByName']);
    Route::get('/pegawai/jabatan/{jabatan}', [PegawaiController::class, 'searchByJabatan']);
    Route::put('/pegawai/reset-password/{id}', [PegawaiController::class, 'resetPassword']);
    Route::get('/pegawai/{id}', [PegawaiController::class, 'searchById']);
    Route::put('/pegawai/reset-password/{id}', [PegawaiController::class, 'resetPassword']);
    Route::get('/kurirP', [PegawaiController::class, 'getPegawaiData']);


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
    Route::put('/barang/{id}', [BarangController::class, 'updatePublic']);
    Route::patch('/barang/{id}/status', [BarangController::class, 'updateStatus']);
    Route::get('indexOwner', [BarangController::class, 'indexOwner']);
    Route::post('/indexOwner-p', [BarangController::class, 'getBarangByPenitipAndMonth']);


    // ======================= Kategori Barang =======================
    Route::get('/kategori', [KategoriBarangController::class, 'index']);


    // ======================= Penitip =======================
    Route::get('/penitip', [PenitipController::class, 'index']);
    Route::post('/penitip', [PenitipController::class, 'store']);
    Route::get('/penitipD', [PenitipController::class, 'getPenitipData']);
    Route::put('/penitip/{id}', [PenitipController::class, 'update']);
    Route::delete('/penitip/{id}', [PenitipController::class, 'destroy']);
    Route::get('/penitip/search/{name}', [PenitipController::class, 'searchByName']);
    Route::get('/penitip/{id}', [PenitipController::class, 'searchById']);
    Route::get('/penitip-id', [PenitipController::class, 'getAllPenitipIds']);



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


    // ======================= Request Donasi =======================
    Route::get('/request-donasi', [RequestDonasiController::class, 'index']);
    Route::post('/request-donasi', [RequestDonasiController::class, 'store']);
    Route::get('/request-donasi/{id}', [RequestDonasiController::class, 'show']);
    Route::put('/request-donasi/{id}', [RequestDonasiController::class, 'update']);
    Route::delete('/request-donasi/{id}', [RequestDonasiController::class, 'destroy']);
    Route::get('/request-donasi/searchByIdOrganisasi/{id}', [RequestDonasiController::class, 'searchByIdOrganisasi']);
    Route::get('/request/owner', [RequestDonasiController::class, 'indexRequestDonasiOwner']);


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
    Route::get('/pembelid', [PembeliController::class, 'getPembeliData']);
    Route::get('/pembeli/search/{name}', [PembeliController::class, 'searchByName']);
    Route::get('/pembeli/searchByEmail/{email}', [PembeliController::class, 'searchByEmail']);
    Route::post('/pembeli/reset-password', [PembeliController::class, 'resetPassword']);


    // ======================= Detail Pengiriman =======================
    Route::get('/detail-pengiriman', [DetailPengirimanController::class, 'index']);
    Route::post('/detail-pengiriman', [DetailPengirimanController::class, 'store']);
    Route::get('/detail-pengiriman/{id}', [DetailPengirimanController::class, 'show']);
    Route::put('/detail-pengiriman/{id}', [DetailPengirimanController::class, 'update']);
    Route::delete('/detail-pengiriman/{id}', [DetailPengirimanController::class, 'destroy']);


    // ======================= Transaksi Penitipan =======================
    Route::get('/transaksi-penitipan', [TransaksiPenitipanController::class, 'index']);
    Route::post('/transaksi-penitipan', [TransaksiPenitipanController::class, 'store']);
    Route::get('/transaksi-penitipan/{id}', [TransaksiPenitipanController::class, 'show']);
    Route::put('/transaksi-penitipan/{id}', [TransaksiPenitipanController::class, 'update']);
    Route::delete('/transaksi-penitipan/{id}', [TransaksiPenitipanController::class, 'destroy']);


    // ======================= Transaksi Penjualan =======================
    Route::post('/transaksi-penjualan', [TransaksiPenjualanController::class, 'store']);
    Route::get('/transaksi-penjualanA', [TransaksiPenjualanController::class, 'indexAdmin']);
    Route::get('/transaksi-penjualanP', [TransaksiPenjualanController::class, 'indexPembeli']);
    Route::put('/verifikasi/{id}', [TransaksiPenjualanController::class, 'verifikasiPembayaran']);


    // ======================= Pengambilan =======================
    Route::get('/pengambilan', [PengambilanController::class, 'index']);
    Route::get('/pengambilan/{id}', [PengambilanController::class, 'show']);
    Route::put('/pengambilan/{id}', [PengambilanController::class, 'update']);
    Route::delete('/pengambilan/{id}', [PengambilanController::class, 'destroy']);
    Route::get('/pengambilan/searchByIdPembeli/{id}', [PengambilanController::class, 'searchByIdPembeli']);
    Route::get('/pengambilan/searchByIdTransaksi/{id}', [PengambilanController::class, 'searchByIdTransaksi']);
    Route::get('/pengambilanP', [PengambilanController::class, 'indexProgres']);
    Route::get('/transaksi-pengiriman/proses-hangus', [PengambilanController::class, 'prosesTransaksiHangusOtomatis']);


    // ======================= Transaksi Cetak PDF =======================
    Route::get('/detail-transaksi', [TransaksiPenjualanController::class, 'indexPdf']);


    // ======================= Transaksi Donasi =======================
    Route::get('/transaksi-donasi', [TransaksiDonasiController::class, 'index']);
    Route::post('/transaksi-donasi', [TransaksiDonasiController::class, 'store']);
    Route::get('/transaksi-donasi/{id}', [TransaksiDonasiController::class, 'show']);
    Route::put('/transaksi-donasi/{id}', [TransaksiDonasiController::class, 'update']);
    Route::delete('/transaksi-donasi/{id}', [TransaksiDonasiController::class, 'destroy']);
    Route::get('/transaksi-donasi/searchByIdOrganisasi/{id}', [TransaksiDonasiController::class, 'searchByIdOrganisasi']);
    Route::get('/donasi/owner', [TransaksiDonasiController::class, 'indexDonasiOwner']);


    // ======================= Owner =======================
    Route::get('/owner', [OwnerController::class, 'indexOwner']);
    Route::get('/penjualan-bulanan', [OwnerController::class, 'PenjualanBulanan']);


    // ======================= Cart =======================
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::get('/cart/{id}', [CartController::class, 'show']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::patch('/cart/{id}/update-transaksi', [CartController::class, 'updateTransaksi']);

    // ======================= Pengiriman =======================
    Route::get('/kurir-pengiriman', [TransaksiPengirimanController::class, 'index']);
    Route::get('/pengiriman/selesai', [TransaksiPengirimanController::class, 'selesai']);
    Route::get('/pengiriman/proses', [TransaksiPengirimanController::class, 'proses']);
    Route::put('/pengiriman/{id}/selesai', [TransaksiPengirimanController::class, 'updateStatusSelesai']);
    Route::get('/kurir-barang', [TransaksiPengirimanController::class, 'selesaiBarang']);
    Route::get('/transaksi-proses', [TransaksiPengirimanController::class, 'transaksiDisiapkanPembeli']);
    Route::post('transaksi-batal/{id}', [TransaksiPengirimanController::class, 'batalkanTransaksiDisiapkan']);

    // Tarik saldo penitip=======================================
    Route::patch('/penitip/tarik-saldo/{id}', [PenitipController::class, 'tarikSaldo']);





    Route::get('/barangHunter', [BarangController::class, 'barangHunterLogin']);
    Route::get('/hunter-history', [TransaksiPenjualanController::class, 'indexHunterHistory']);
    // Route untuk hunter yang login dan ingin melihat history transaksinya
Route::get('/transaksi/hunter/history', [TransaksiPenjualanController::class, 'indexHunterHistory']);

// Route untuk pegawai dengan jabatan ID = 5
Route::get('/transaksi/jabatan-5', [TransaksiPenjualanController::class, 'getTransaksiByJabatan5']);
    // Route::get('/transaksi-public', [TransaksiPenjualanController::class, 'indexHunterHistory']);

    // ======================= Rating =======================
    Route::get('/rating', [RatingController::class, 'index']);
    Route::post('/rating', [RatingController::class, 'store']);

    // Endpoint privat (perlu login, gunakan sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/penukaran-merchandise-login', [PenukaranMerchandiseController::class, 'index']);
        Route::post('/penukaran-merchandise-login', [PenukaranMerchandiseController::class, 'store']);
        Route::get('/penukaran-merchandise/{id}', [PenukaranMerchandiseController::class, 'show']);
        Route::delete('/penukaran-merchandise/{id}', [PenukaranMerchandiseController::class, 'destroy']);
    });
    Route::get('/penukaran-merchandise', [PenukaranMerchandiseController::class, 'indexPublic']);
    Route::put('penukaran/{id}/input-tanggal', [PenukaranMerchandiseController::class, 'updateTanggalPengambilan']);
});
Route::get('/barang', [BarangController::class, 'indexPublic']);
Route::get('/barang/{id}', [BarangController::class, 'showPublic']);

// Route::get('/penukaran-merchandise', [PenukaranMerchandiseController::class, 'indexPublic']);
// Endpoint publik (tidak perlu login)
Route::get('/penukaran-merchandise/show-merchandise/{id}', [PenukaranMerchandiseController::class, 'showMerchandise']);
Route::get('/penukaran-merchandise/list-merchandise', [PenukaranMerchandiseController::class, 'listMerchandise']);


// ======================= Merchandise =======================
Route::get('/merchandise', [MerchandiseController::class, 'index']);
Route::post('/merchandise', [MerchandiseController::class, 'show']);

// Route::get('/transaksi-public', [TransaksiPenjualanController::class, 'publicIndex']);


//Laporan
// routes/api.php
Route::get('/laporan/kategori-barang', [LaporanController::class, 'laporanKategoriBarang']);
