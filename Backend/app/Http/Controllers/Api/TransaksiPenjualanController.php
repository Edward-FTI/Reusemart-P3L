<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use App\Models\DetailPengiriman;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Exception;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        $transaksiPenjualans = TransaksiPenjualan::with(['detailPengiriman', 'pembeli'])->get();
        if ($transaksiPenjualans->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mengambil data transaksi penjualan',
                'data' => $transaksiPenjualans
            ], 200);
        }
        return response([
            'message' => 'Data transaksi penjualan kosong',
            'data' => []
        ], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_detail_pengiriman' => 'required',
            'id_pembeli' => 'required',
            'total_harga' => 'required',
            'status' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        try {
            $transaksiPenjualan = TransaksiPenjualan::create($storeData);
            return response([
                'message' => 'Berhasil menambahkan data transaksi penjualan',
                'data' => $transaksiPenjualan
            ], 201);
        } catch (Exception $e) {
            return response([
                'message' => 'Gagal menambahkan data transaksi penjualan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksiPenjualan = TransaksiPenjualan::with(['detailPengiriman', 'pembeli'])->find($id);
        if ($transaksiPenjualan) {
            return response([
                'message' => 'Berhasil mengambil data transaksi penjualan',
                'data' => $transaksiPenjualan
            ], 200);
        }
        return response([
            'message' => 'Data transaksi penjualan tidak ditemukan',
            'data' => []
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksiPenjualan = TransaksiPenjualan::find($id);
        if (!$transaksiPenjualan) {
            return response([
                'message' => 'Data transaksi penjualan tidak ditemukan',
                'data' => null
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            'id_detail_pengiriman' => 'required',
            'id_pembeli' => 'required',
            'total_harga' => 'required',
            'status' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $transaksiPenjualan->id_detail_pengiriman = $request->id_detail_pengiriman;
        $transaksiPenjualan->id_pembeli = $request->id_pembeli;
        $transaksiPenjualan->total_harga = $request->total_harga;
        $transaksiPenjualan->status = $request->status;
        $transaksiPenjualan->save();
        return response([
            'message' => 'Berhasil mengupdate data transaksi penjualan',
            'data' => $transaksiPenjualan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksiPenjualan = TransaksiPenjualan::find($id);
        if (!$transaksiPenjualan) {
            return response([
                'message' => 'Data transaksi penjualan tidak ditemukan',
                'data' => null
            ], 404);
        }
        $transaksiPenjualan->delete();
        return response([
            'message' => 'Berhasil menghapus data transaksi penjualan',
            'data' => $transaksiPenjualan
        ], 200);
    }

    public function getPembeli()
    {
        $pembeli = Pembeli::all();
        if ($pembeli->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mengambil data pembeli',
                'data' => $pembeli
            ], 200);
        }
        return response([
            'message' => 'Data pembeli kosong',
            'data' => []
        ], 400);
    }
    public function getDetailPengiriman()
    {
        $detailPengiriman = DetailPengiriman::all();
        if ($detailPengiriman->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mengambil data detail pengiriman',
                'data' => $detailPengiriman
            ], 200);
        }
        return response([
            'message' => 'Data detail pengiriman kosong',
            'data' => []
        ], 400);
    }
    public function searchByIdPembeli($id)
    {
        $transaksiPenjualans = TransaksiPenjualan::where('id_pembeli', $id)->get();
        if ($transaksiPenjualans->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mengambil data transaksi penjualan',
                'data' => $transaksiPenjualans
            ], 200);
        }
        return response([
            'message' => 'Data transaksi penjualan tidak ditemukan',
            'data' => []
        ], 404);
    }
}
