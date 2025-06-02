<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Barang;
use App\Models\TransaksiPengiriman; // Menggunakan model TransaksiPengiriman
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\TransaksiPenjualan; // Menggunakan model TransaksiPenjualan
use App\Models\Detail_transaksi_penjualan; // Menggunakan model Detail_transaksi_penjualan

class PengambilanController extends Controller
{
    /**
     * Mengambil ID Pegawai Gudang yang sedang login.
     *
     * @return int|null ID Pegawai jika ditemukan dan memiliki peran 'Pegawai Gudang', jika tidak null.
     */
    private function getPegawaiId()
    {
        $user = Auth::user();

        // Memastikan pengguna login dan memiliki peran 'Pegawai Gudang'
        if (!$user || $user->role !== 'Pegawai Gudang') {
            return null;
        }

        // Mencari data pegawai berdasarkan email pengguna yang login
        $pegawai = Pegawai::where('email', $user->email)->first();

        // Mengembalikan ID pegawai jika ditemukan
        return $pegawai?->id;
    }


    /**
     * Menampilkan daftar transaksi pengambilan (pengiriman) dengan status "proses".
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Mengambil semua transaksi pengiriman dengan status "proses"
        // Memuat relasi 'transaksiPenjualan' dengan 'pembeli' dan 'detail' bersarang,
        // dan 'detail' juga memuat 'barang' bersarang.
        $pengambilan = TransaksiPengiriman::with(['transaksiPenjualan.pembeli', 'transaksiPenjualan.detail.barang'])
            ->where('status_pengiriman', 'proses') // Filter berdasarkan status pengiriman
            ->get();

        // Memeriksa apakah ada data transaksi pengiriman
        if ($pengambilan->isNotEmpty()) {
            return response()->json([
                'message' => 'Berhasil mengambil data pengambilan',
                'data' => $pengambilan // Data akan mencakup relasi yang dimuat
            ], 200);
        }

        // Jika tidak ada data transaksi pengiriman
        return response()->json([
            'message' => 'Data pengambilan kosong',
            'data' => []
        ], 200);
    }

    /**
     * Menyimpan transaksi pengambilan baru atau memperbarui status transaksi yang ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input request
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required|exists:transaksi_pengirimans,id', // Validasi ID transaksi pengiriman
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mencari transaksi pengiriman berdasarkan ID
        $transaksi = TransaksiPengiriman::find($request->id_transaksi);

        // Jika transaksi tidak ditemukan
        if (!$transaksi) {
            return response()->json([
                'message' => 'Transaksi Pengiriman tidak ditemukan'
            ], 404);
        }

        // Memperbarui status pengiriman menjadi 'diambil'
        $transaksi->status_pengiriman = 'diambil';
        $transaksi->save();

        return response()->json([
            'message' => 'Pengambilan berhasil disimpan',
            'data' => $transaksi
        ], 200);
    }

    /**
     * Menampilkan detail transaksi pengambilan berdasarkan ID.
     *
     * @param  string  $id ID transaksi pengiriman
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Mencari transaksi pengiriman berdasarkan ID dan memuat relasi 'transaksiPenjualan'
        // dengan 'pembeli' dan 'detail' bersarang, dan 'detail' juga memuat 'barang' bersarang.
        $transaksi = TransaksiPengiriman::with(['transaksiPenjualan.pembeli', 'transaksiPenjualan.detail.barang'])->find($id);

        // Jika transaksi tidak ditemukan
        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => "Detail pengambilan ID: $id",
            'data' => $transaksi // Data akan mencakup relasi yang dimuat
        ], 200);
    }

    /**
     * Memperbarui status transaksi pengambilan berdasarkan ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id ID transaksi pengiriman
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPengiriman::find($id);

        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        // Update field yang dikirim, fallback ke data lama kalau tidak ada
        $transaksi->tgl_pengiriman = $request->input('tgl_pengiriman', $transaksi->tgl_pengiriman);
        $transaksi->status_pengiriman = $request->input('status_pengiriman', $transaksi->status_pengiriman);
        $transaksi->catatan = $request->input('catatan', $transaksi->catatan);
        $transaksi->id_pegawai = $request->input('id_pegawai', $transaksi->id_pegawai);

        $transaksi->save();

        return response()->json([
            'message' => "Pengambilan ID $id berhasil diperbarui",
            'data' => $transaksi
        ], 200);
    }


    /**
     * Menghapus transaksi pengambilan berdasarkan ID.
     *
     * @param  string  $id ID transaksi pengiriman
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Mencari transaksi pengiriman berdasarkan ID
        $transaksi = TransaksiPengiriman::find($id);

        // Jika transaksi tidak ditemukan
        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        // Menghapus transaksi
        $transaksi->delete();

        return response()->json([
            'message' => "Pengambilan ID $id berhasil dihapus"
        ], 200);
    }
}
