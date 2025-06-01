<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

// use App\Models\DetailTransaksiPenjualan;
// use App\Models\Detail_transaksi_penjualan;
use App\Models\Barang;
use App\Models\TransaksiPenjualan;
use App\Models\Pegawai;
use App\Models\Pembeli;

class PengambilanController extends Controller
{
    private function getPegawaiId()
{
    $user = Auth::user();

    if (!$user || $user->role !== 'Pegawai Gudang') {
        return null;
    }

    $pegawai = Pegawai::where('email', $user->email)->first();

    return $pegawai?->id;
}


    public function index()
    {
        // Ambil semua transaksi penjualan dengan status "proses"
        // Menggunakan 'with' untuk memuat relasi 'detail' dan 'pembeli'
        // 'detail.barang' akan memuat barang yang terkait dengan setiap detail transaksi
        $pengambilan = TransaksiPenjualan::with(['detail.barang', 'pembeli'])
            ->where('status_pengiriman', 'proses')
            ->get();


        if ($pengambilan->isNotEmpty()) {
            return response()->json([
                'message' => 'Berhasil mengambil data pengambilan',
                'data' => $pengambilan // Data ini akan mencakup relasi yang dimuat
            ], 200);
        }

        return response()->json([
            'message' => 'Data pengambilan kosong',
            'data' => []
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required|exists:transaksi_penjualans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaksi = TransaksiPenjualan::find($request->id_transaksi);
        $transaksi->status_pengiriman = 'diambil';
        $transaksi->save();

        return response()->json([
            'message' => 'Pengambilan berhasil disimpan',
            'data' => $transaksi
        ], 200);
    }

    public function show($id)
    {
        // Menggunakan 'with' untuk memuat relasi 'detail' dan 'pembeli' untuk transaksi tunggal
        // 'detail.barang' akan memuat barang yang terkait dengan setiap detail transaksi
        $transaksi = TransaksiPenjualan::with(['detail.barang', 'pembeli'])->find($id);

        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => "Detail pengambilan ID: $id",
            'data' => $transaksi // Data ini akan mencakup relasi yang dimuat
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPenjualan::find($id);

        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        $transaksi->status_pengiriman = $request->input('status_pengiriman', $transaksi->status_pengiriman);
        $transaksi->save();

        return response()->json([
            'message' => "Pengambilan ID $id berhasil diperbarui",
            'data' => $transaksi
        ], 200);
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPenjualan::find($id);

        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        $transaksi->delete();

        return response()->json([
            'message' => "Pengambilan ID $id berhasil dihapus"
        ], 200);
    }
}
