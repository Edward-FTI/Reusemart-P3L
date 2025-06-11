<?php

namespace App\Http\Controllers\Api;

use App\Models\TransaksiDonasi;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Exception;

class TransaksiDonasiController extends Controller
{
    private function getOrganisasiId()
    {
        $userEmail = Auth::user()->email;
        $organisasi = Organisasi::where('email', $userEmail)->first();

        if (!$organisasi) {
            return null;
        }

        return $organisasi->id;
    }

    public function index()
    {
        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $transaksiDonasi = TransaksiDonasi::where('id_organisasi', $organisasiId)->get();

            return response()->json([
                'message' => 'Data transaksi donasi berhasil diambil',
                'data' => $transaksiDonasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data transaksi donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|string',
            'nama_penitip' => 'required|string',
            'jenis_barang' => 'required|string',
            'jumlah_barang' => 'required|integer',
            'tgl_transaksi' => 'required|date',
        ]);

        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $transaksiDonasi = TransaksiDonasi::create([
                'id_organisasi' => $organisasiId,
                'status' => $request->status,
                'nama_penitip' => $request->nama_penitip,
                'jenis_barang' => $request->jenis_barang,
                'jumlah_barang' => $request->jumlah_barang,
                'tgl_transaksi' => $request->tgl_transaksi,
            ]);

            return response()->json([
                'message' => 'Data transaksi donasi berhasil disimpan',
                'data' => $transaksiDonasi
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data transaksi donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $transaksiDonasi = TransaksiDonasi::where('id_organisasi', $organisasiId)->where('id', $id)->first();

            if (!$transaksiDonasi) {
                return response()->json(['message' => 'Data transaksi donasi tidak ditemukan'], 404);
            }

            return response()->json([
                'message' => 'Data transaksi donasi berhasil diambil',
                'data' => $transaksiDonasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data transaksi donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|string',
            'nama_penitip' => 'required|string',
            'jenis_barang' => 'required|string',
            'jumlah_barang' => 'required|integer',
            'tgl_transaksi' => 'required|date',
        ]);

        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $transaksiDonasi = TransaksiDonasi::where('id_organisasi', $organisasiId)->where('id', $id)->first();

            if (!$transaksiDonasi) {
                return response()->json(['message' => 'Data transaksi donasi tidak ditemukan'], 404);
            }

            $transaksiDonasi->update($request->all());

            return response()->json([
                'message' => 'Data transaksi donasi berhasil diperbarui',
                'data' => $transaksiDonasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data transaksi donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $transaksiDonasi = TransaksiDonasi::where('id_organisasi', $organisasiId)->where('id', $id)->first();

            if (!$transaksiDonasi) {
                return response()->json(['message' => 'Data transaksi donasi tidak ditemukan'], 404);
            }

            $transaksiDonasi->delete();

            return response()->json([
                'message' => 'Data transaksi donasi berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data transaksi donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function indexDonasiOwner(): JsonResponse
    {
        $user = Auth::user();
        if (!$user || strtolower($user->role) !== 'owner') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $transaksis = TransaksiDonasi::with(['barang', 'penitip', 'organisasi'])
            ->where('status', 'selesai')
            ->get();

        $data = $transaksis->map(function ($transaksi) {
            return [
                'kode_produk'     => strtoupper(substr($transaksi->barang->nama_barang, 0, 1)) . $transaksi->id_barang,
                'nama_produk'     => $transaksi->barang->nama_barang,
                'id_penitip'      => 'T' . $transaksi->id_penitip,
                'nama_penitip'    => $transaksi->penitip->nama_penitip,
                'tanggal_donasi'  => $transaksi->tgl_transaksi->format('Y-m-d'),
                'organisasi'      => $transaksi->organisasi->nama,
                'nama_penerima'   => $transaksi->nama_penerima,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Transaksi Donasi Berhasil Diambil',
            'data'    => $data,
        ]);
    }
}
