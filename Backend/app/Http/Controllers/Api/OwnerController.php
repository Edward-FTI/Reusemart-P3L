<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\Request_Donasi;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function indexOwner() {
        $request = Request_Donasi::all();
        if(count($request) > 0) {
            return response([
                'message' => 'Berhasil mengambil data request donasi',
                'data' => $request,
            ], 200);
        }
        return response([
            'message' => 'Gagal mengambil data request donasi',
            'data' => null,
        ], 400);
    }

    public function PenjualanBulanan() {
        $transaksi = TransaksiPenjualan::all();

        if(!$transaksi) {
            return response([
                'message' => "Tidak ada data transaksi",
                'data' => null,
            ], 404);
        }
        return response([
            'message' => "Berhasil ambil seluruh data transaksi",
            'data' => $transaksi,
        ], 200);
    }

}
