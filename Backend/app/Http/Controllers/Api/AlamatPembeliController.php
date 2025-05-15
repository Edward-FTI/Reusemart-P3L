<?php

namespace App\Http\Controllers\Api;

use App\Models\AlamatPembeli;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class AlamatPembeliController extends Controller
{
    // Ambil id_pembeli dari email user yang login
    private function getPembeliId()
    {
        $userEmail = Auth::user()->email;
        $pembeli = Pembeli::where('email', $userEmail)->first();

        if (!$pembeli) {
            return null;
        }

        return $pembeli->id;
    }

    // Menampilkan semua alamat milik user yang login
    public function index()
    {
        try {
            $pembeliId = $this->getPembeliId();

            if (!$pembeliId) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
            }

            $alamat = AlamatPembeli::where('id_pembeli', $pembeliId)->get();

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
            $pembeliId = $this->getPembeliId();

            if (!$pembeliId) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
            }

            $alamat = AlamatPembeli::where('id_pembeli', $pembeliId)->where('id', $id)->first();

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
            $pembeliId = $this->getPembeliId();

            if (!$pembeliId) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
            }

            $alamat = AlamatPembeli::create([
                'id_pembeli' => $pembeliId,
                'alamat' => $validatedData['alamat'],
            ]);

            return response()->json([
                'message' => 'Alamat berhasil ditambahkan',
                'data' => $alamat
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan alamat',
                'error' => $e->getMessage()
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
            $pembeliId = $this->getPembeliId();

            if (!$pembeliId) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
            }

            $alamat = AlamatPembeli::where('id_pembeli', $pembeliId)->where('id', $id)->first();

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
            $pembeliId = $this->getPembeliId();

            if (!$pembeliId) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
            }

            $alamat = AlamatPembeli::where('id_pembeli', $pembeliId)->where('id', $id)->first();

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
