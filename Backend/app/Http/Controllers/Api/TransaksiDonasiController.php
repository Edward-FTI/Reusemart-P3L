<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransaksiDonasiController extends Controller
{
    public function index()
    {
        $donasi = TransaksiDonasi::with('organisasi')->get();

        if(count($donasi) > 0) {
            return response([
                'message' => 'Berhasil mengambil data transaksi donasi',
                'data' => $donasi
            ], 200);
        }
        return response([
            'message' => 'Data transaksi donasi kosong',
            'data' => []
        ], 400);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $donasi = TransaksiDonasi::find($id);
        if(is_null($donasi)) {
            return response([
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }
        $updateDonasi = $request->all();
        $validate = Validator::make($updateDonasi, [
            'status',
        ]);
        
        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }
        $donasi->status = $updateDonasi['status'];

        if($donasi->update($updateDonasi)) {
            return response([
                'message' => 'Berhasil update transaksi donasi',
                'data' => $donasi
            ], 200);
        }

        return response([
            'message' => 'Gagal update data transaksi donasi',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
