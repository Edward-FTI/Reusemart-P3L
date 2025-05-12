<?php

namespace App\Http\Controllers\Api;

use App\Models\AlamatPembeli;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class AlamatPembeliController extends Controller
{
    // Menampilkan semua alamat milik user yang login
   public function index()
{
    try {
        $userId = (int) Auth::id();
        $targetId = $userId + 2;

        $alamat = AlamatPembeli::where('id_pembeli', $targetId)->get();

        return response()->json([
            'message' => 'Data alamat berhasil diambil',
            'data' => $alamat
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Gagal mengambil data alamat',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // Menampilkan alamat spesifik
    public function show($id)
    {
        try {
            
            
            $userId = Auth::id();
            $alamat = AlamatPembeli::where('id_pembeli', $userId)->where('id', $id)->first();

            if (!$alamat) {
                return response()->json(['message' => 'Alamat tidak ditemukan atau Anda tidak memiliki izin'], 404);
            }

            return response()->json([
                'message' => 'Data alamat berhasil diambil',
                'data' => $alamat
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data alamat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menambahkan alamat baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'alamat' => 'required|string',
        ]);

        try {
            $userId = Auth::id();
            $alamat = AlamatPembeli::create([
                'id_pembeli' => $userId+2,
                'alamat' => $validatedData['alamat'],
            ]);

            return response()->json([
                'message' => 'Alamat berhasil ditambahkan',
                'data' => $alamat
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan alamat',
                'error' => $e->getMessage(),
                // 'id' => $userId+2
            ], 500);
        }
    }

    // Memperbarui alamat
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'alamat' => 'required|string',
        ]);

        try {
            $userId = Auth::id();
            $alamat = AlamatPembeli::where('id_pembeli', $userId)->where('id', $id)->first();

            if (!$alamat) {
                return response()->json(['message' => 'Alamat tidak ditemukan atau Anda tidak memiliki izin'], 404);
            }

            $alamat->update([
                'alamat' => $validatedData['alamat'],
            ]);

            return response()->json([
                'message' => 'Alamat berhasil diperbarui',
                'data' => $alamat
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui alamat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menghapus alamat
    public function destroy($id)
    {
        try {
            $userId = Auth::id();
            $alamat = AlamatPembeli::where('id_pembeli', $userId)->where('id', $id)->first();

            if (!$alamat) {
                return response()->json(['message' => 'Alamat tidak ditemukan atau Anda tidak memiliki izin'], 404);
            }

            $alamat->delete();

            return response()->json(['message' => 'Alamat berhasil dihapus']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus alamat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
