<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    /**
     * Menampilkan semua data merchandise (dengan relasi penukaran).
     */
    public function index()
    {
        $merchandises = Merchandise::with('penukaranMerchandise')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Merchandise',
            'data' => $merchandises
        ], 200);
    }

    /**
     * Menampilkan detail merchandise berdasarkan ID (dengan relasi penukaran).
     */
    public function show(string $id)
    {
        $merchandise = Merchandise::with('penukaranMerchandise')->find($id);

        if (!$merchandise) {
            return response()->json([
                'success' => false,
                'message' => 'Merchandise tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Merchandise',
            'data' => $merchandise
        ], 200);
    }

    // Fungsi lainnya (store, update, destroy) masih kosong dan bisa ditambahkan nanti.
}
