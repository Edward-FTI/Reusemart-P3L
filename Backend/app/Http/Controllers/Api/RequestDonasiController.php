<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\Request_Donasi;
use App\Models\Organisasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class RequestDonasiController extends Controller
{

    public function indexOwner()
    {
        $request = Request_Donasi::all();
        if (count($request) > 0) {
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

            $requestDonasi = Request_Donasi::where('id_organisasi', $organisasiId)->get();

            return response()->json([
                'message' => 'Data request donasi berhasil diambil',
                'data' => $requestDonasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data request donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'request' => 'required|string',
        ]);

        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $requestDonasi = Request_Donasi::create([
                'id_organisasi' => $organisasiId,
                'request' => $request->input('request'),
            ]);

            return response()->json([
                'message' => 'Request donasi berhasil dibuat',
                'data' => $requestDonasi
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat request donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $requestDonasi = Request_Donasi::where('id_organisasi', $organisasiId)->where('id', $id)->first();

            if (!$requestDonasi) {
                return response()->json(['message' => 'Request donasi tidak ditemukan'], 404);
            }

            return response()->json([
                'message' => 'Data request donasi berhasil diambil',
                'data' => $requestDonasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data request donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'request' => 'required|string',
        ]);

        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $requestDonasi = Request_Donasi::where('id_organisasi', $organisasiId)->where('id', $id)->first();

            if (!$requestDonasi) {
                return response()->json(['message' => 'Request donasi tidak ditemukan'], 404);
            }

            $requestDonasi->update([
                'request' => $request->input('request'),
            ]);

            return response()->json([
                'message' => 'Request donasi berhasil diperbarui',
                'data' => $requestDonasi
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui request donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $organisasiId = $this->getOrganisasiId();

            if (!$organisasiId) {
                return response()->json(['message' => 'Data organisasi tidak ditemukan untuk user yang login'], 404);
            }

            $requestDonasi = Request_Donasi::where('id_organisasi', $organisasiId)->where('id', $id)->first();

            if (!$requestDonasi) {
                return response()->json(['message' => 'Request donasi tidak ditemukan'], 404);
            }

            $requestDonasi->delete();

            return response()->json([
                'message' => 'Request donasi berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus request donasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
