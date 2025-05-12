<?php

namespace App\Http\Controllers\Api;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class OrganisasiController extends Controller
{
    /**
     * Display a listing of the organizations.
     */
    public function index(): \Illuminate\Http\Response
    {
        $organisasis = Organisasi::with('transaksi_donasi')->get();

        if ($organisasis->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mengambil data organisasi',
                'data' => $organisasis
            ], 200);
        }

        return response([
            'message' => 'Data organisasi kosong',
            'data' => []
        ], 400);
    }


    // Register organisasi baru
    public function registerOrg(Request $request)

    {
        $storeData = $request->all();

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:organisasis',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8',
            'permintaan' => 'nullable|string',
        ]);
        $permintaan = $request->permintaan ?? '';
        $organisasi = Organisasi::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'permintaan' => $permintaan,
        ]);


        return response([
            'message' => 'Berhasil menambahkan data organisasi',
            'data' => $organisasi
        ], 201);
    }

    /**
     * Update the specified organization in storage.
     */
      // Update organisasi (termasuk permintaan)
    public function update(Request $request, $id)
    {
        $organisasi = Organisasi::find($id);
        if (!$organisasi) {
            return response()->json(['message' => 'Organisasi not found'], 404);
        }

        $validatedData = $request->validate([
            'nama' => 'string|max:255|nullable',
            'alamat' => 'string|max:255|nullable',
            'email' => 'required|email',
            'no_hp' => 'string|max:15|nullable',
            'password' => 'string|min:8|nullable',
            'permintaan' => 'string|nullable',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $organisasi->update($validatedData);

        return response()->json([
            'organisasi' => $organisasi,
            'message' => 'Organisasi updated successfully',
        ]);
    }


    /**
     * Remove the specified organization from storage.
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        $organisasi = Organisasi::find($id);
        if (!$organisasi) {
            return response(['message' => 'Data organisasi tidak ditemukan', 'data' => null], 404);
        }

        $organisasi->delete();

        return response([
            'message' => 'Berhasil menghapus data organisasi',
            'data' => $organisasi
        ], 200);
    }

    /**
     * Display the specified organization.
     */
    public function show(string $id): \Illuminate\Http\Response
    {
        $organisasi = Organisasi::find($id);

        if ($organisasi) {
            return response([
                'message' => 'Organisasi ditemukan',
                'data' => $organisasi
            ], 200);
        }

        return response([
            'message' => 'Data tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Search organizations by name.
     */
    public function searchByName(string $name): \Illuminate\Http\Response
    {
        $organisasis = Organisasi::where('nama', 'LIKE', '%' . $name . '%')->get();

        if ($organisasis->isEmpty()) {
            return response([
                'message' => 'Organisasi dengan nama tersebut tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response([
            'message' => 'Berhasil mengambil data organisasi',
            'data' => $organisasis
        ], 200);
    }

    /**
     * Search organization by ID.
     */
    public function searchById(string $id): \Illuminate\Http\Response
    {
        $organisasi = Organisasi::find($id);

        if (!$organisasi) {
            return response([
                'message' => 'Organisasi tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response([
            'message' => 'Organisasi ditemukan',
            'data' => $organisasi
        ], 200);
    }
}