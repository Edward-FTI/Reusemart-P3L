<?php

namespace App\Http\Controllers\Api;

use App\Models\TransaksiPengiriman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use App\Models\Pegawai;
use DateTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function getPegawaiId()
    {
        $userEmail = Auth::user()->email;
        $pegawai = Pegawai::where('email', $userEmail)->first();

        if (!$pegawai) {
            return null;
        }
        return $pegawai->id;
    }

    public function indexPublic()
    {
        $pengiriman = TransaksiPengiriman::with(['barang', 'pegawai'])->get();
        if (count($pengiriman) > 0) {
            return response([
                'message' => 'Berhasil mengambil data pengiriman',
                'data' => $pengiriman
            ], 200);
        }
        return response([
            'message' => 'Data Pengiriman kosong',
            'data' => []
        ], 200);
    }


    public function index()
    {
        $pegawaiId = $this->getPegawaiId();

        if (!$pegawaiId) {
            return response([
                'message' => 'Pegawai tidak ditemukan untuk user yang login'
            ], 404);
        }

        $pengiriman = TransaksiPengiriman::with(['barang', 'pegawai'])
            ->where('id_pegawai', $pegawaiId)
            ->get();

        if ($pengiriman->count() > 0) {
            return response([
                'message' => 'Berhasil mengambil data transaksi pengiriman',
                'data' => $pengiriman
            ], 200);
        }
        return response([
            'message' => 'Data Pengiriman kosong',
            'data' => []
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengiriman = TransaksiPengiriman::with(['barang', 'pegawai'])->find($id);

        if (!$pengiriman) {
            return response([
                'message' => 'Data Pengiriman tidak ditemukan'
            ], 404);
        }

        return response([
            'message' => 'Berhasil mengambil data pengiriman',
            'data' => $pengiriman
        ], 200);
    }

    public function showAllBarangs(string $id)
    {
        $barang = Barang::find($id);
        if(!is_null($barang)) {
            return response([
                'message' => 'Berhasil mengambil data barang',
                'data' => $barang
            ], 200);
        }
        return response([
            'message' => 'Barang tidak ditemukan',
            'data' => null
        ], 404);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
