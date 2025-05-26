<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use App\Models\TransaksiPenitipan;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransaksiPenitipanController extends Controller
{
    private function getPenitipId()
    {
        $userEmail = Auth::user()->email;
        $penitip = Penitip::where('email', $userEmail)->first();

        if (!$penitip) {
            return null;
        }
        return $penitip->id;
    }

    public function indexPublic()
    {
        $barang = Barang::with(['penitip', 'kategori_barang'])->get();
        if (count($barang) > 0) {
            return response([
                'message' => 'Berhasil mengambil data barang',
                'data' => $barang
            ], 200);
        }
        return response([
            'message' => 'Data Barang kosong',
            'data' => []
        ], 200);
    }

    public function index()
    {
        $penitipId = $this->getPenitipId();

        if (!$penitipId) {
            return response([
                'message' => 'Penitip tidak ditemukan untuk user yang login'
            ], 404);
        }
        $transaksi = Barang::with(['penitip', 'kategori_barang'])
            ->where('id_penitip', $penitipId)
            ->get();

        if ($transaksi->count() > 0) {
            return response([
                'message' => 'Berhasil mengambil data transaksi penitipan',
                'data' => $transaksi
            ], 200);
        }
        return response([
            'message' => 'Data Transaksi Penitipan kosong',
            'data' => []
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penitipId = $this->getPenitipId();
        $barang = Barang::with($id);

        if (!$barang) {
            return response([
                'message' => 'Barang tidak ditemukan',
                'data' => null
            ], 404);
        }
        if ($barang->id_penitip != $penitipId) {
            return response([
                'message' => 'Barang tidak ditemukan untuk penitip ini',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'Barang dengan nama ' . $barang->nama_barang . ' ditemukan',
            'data' => $barang
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penitipId = $this->getPenitipId();

        // Cek apakah barangnya ada
        $barang = Barang::find($id);

        if (!$barang) {
            return response([
                'message' => 'Barang tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Pastikan barang itu milik penitip yang login
        if ($barang->id_penitip != $penitipId) {
            return response([
                'message' => 'Anda tidak berhak mengubah barang ini',
                'data' => null
            ], 403);
        }

        // Validasi input (opsional, tapi direkomendasikan)
        $validator = Validator::make($request->all(), [
            'tgl_penitipan' => 'nullable|date',
            'masa_penitipan' => 'nullable|date',
            'penambahan_durasi' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Lakukan update
        $barang->update($request->only(['tgl_penitipan', 'masa_penitipan', 'penambahan_durasi']));

        return response([
            'message' => 'Data barang berhasil diperbarui',
            'data' => $barang
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
