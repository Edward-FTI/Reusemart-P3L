<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailPengirimanController extends Controller
{
    public function index()
    {
        $data = DetailPengiriman::all();

        return response()->json([
            'message' => $data->isEmpty() ? 'Data DetailPengiriman kosong' : 'Berhasil mengambil data',
            'data' => $data
        ], $data->isEmpty() ? 404 : 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_pengiriman' => 'required|string|max:255',
            'metode_pengiriman' => 'required|string|max:255',
        ]);

        $data = DetailPengiriman::create($validated);

        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $data = DetailPengiriman::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil data',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DetailPengiriman::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'status_pengiriman' => 'required|string|max:255',
            'metode_pengiriman' => 'required|string|max:255',
        ]);

        $data->update($validated);

        return response()->json([
            'message' => 'Berhasil mengupdate data',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = DetailPengiriman::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $data->delete();

        return response()->json([
            'message' => 'Berhasil menghapus data'
        ], 200);
    }

    public function getDetailPengirimanById($id)
    {
        return $this->show($id); // Reuse method show
    }
}
