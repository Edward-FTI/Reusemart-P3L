<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Detail_transaksi_penjualan;
use App\Models\Barang;
use App\Models\Pembeli;
use Illuminate\Support\Facades\Auth;
use Exception;


class RatingController extends Controller
{

    // Mengambil data pembeli yang sedang login saat ini
    private function getPembeliId()
    {
        try {
            $user = Auth::user();
            if (!$user || !isset($user->email)) {
                return response()->json(['message' => 'User belum login atau tidak memiliki email'], 401);
            }
            $pembeli = Pembeli::where('email', $user->email)->first();
            if (!$pembeli) {
                return response()->json(['message' => 'Pembeli tidak ditemukan untuk email tersebut'], 404);
            }
            return $pembeli->id;
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal mengambil ID pembeli', 'error' => $e->getMessage()], 500);
        }
    }


    // Ambil data rating
    public function index()
    {
        try {
            $pembeliId = $this->getPembeliId();
            if (!$pembeliId) {
                return response([
                    'message' => 'Data pembeli tidak ditemukan pada user yang login',
                    'data' => [],
                ], 404);
            }
            $rating = Rating::where('id_pembeli', $pembeliId)->get();

            if ($rating->isEmpty()) {
                return response([
                    'message' => 'Belum ada data rating pada pembeli yang login',
                    'data' => [],
                ], 404);
            }

            return response([
                'message' => 'Berhasil mengambil data pembeli yang login',
                'data' => $rating,
            ], 200);
        } catch (Exception $e) {
            return response([
                'message' => 'Gagal mengambil data Rating',
                'data' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        // Validasi
        $validate = Validator::make($request->all(), [
            'id_detail_transaksi' => 'required',
            'nilai_rating' => 'required|numeric|min:1|max:5',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        // Cek login
        $pembeliId = $this->getPembeliId();
        if (!$pembeliId) {
            return response(['message' => 'User belum login atau pembeli tidak ditemukan'], 401);
        }

        // Cek rating duplikat
        $existingRating = Rating::where('id_detail_transaksi', $request->id_detail_transaksi)
            ->where('id_pembeli', $pembeliId)
            ->first();
        if ($existingRating) {
            return response([
                'message' => 'Anda sudah memberikan rating untuk transaksi ini.',
                'data' => $existingRating
            ], 400);
        }

        // Ambil detail transaksi & barang
        $detail = Detail_transaksi_penjualan::findOrFail($request->id_detail_transaksi);
        $barang = Barang::find($detail->id_barang);
        if (!$barang) {
            return response(['message' => 'Barang tidak ditemukan'], 400);
        }

        // Simpan rating
        $create = Rating::create([
            'id_pembeli' => $pembeliId,
            'id_detail_transaksi' => $request->id_detail_transaksi,
            'id_transaksi_penjualan' => $detail->id_transaksi_penjualan, // penting
            'id_penitip' => $barang->id_penitip,
            'nilai_rating' => $request->nilai_rating,
        ]);

        return response([
            'message' => 'Berhasil menambahkan rating',
            'data' => $create
        ], 201);
    }
}
