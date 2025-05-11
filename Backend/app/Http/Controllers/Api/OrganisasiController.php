<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Store a newly created organization in storage.
     */
    public function store(Request $request): \Illuminate\Http\Response
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'permintaan' => 'nullable|string',
            'email' => 'required|email|unique:organisasis,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $storeData['password'] = Hash::make($storeData['password']);

        $organisasi = Organisasi::create($storeData);

        return response([
            'message' => 'Berhasil menambahkan data organisasi',
            'data' => $organisasi
        ], 201);
    }

    /**
     * Update the specified organization in storage.
     */
    public function update(Request $request, string $id): \Illuminate\Http\Response
    {
        $organisasi = Organisasi::find($id);
        if (!$organisasi) {
            return response(['message' => 'Data organisasi tidak ditemukan', 'data' => null], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'permintaan' => 'nullable|string',
            'email' => 'required|email|unique:organisasis,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $organisasi->nama = $updateData['nama'];
        $organisasi->alamat = $updateData['alamat'];
        $organisasi->permintaan = $updateData['permintaan'];
        $organisasi->email = $updateData['email'];
        $organisasi->no_hp = $updateData['no_hp'];

        if (!empty($updateData['password'])) {
            $organisasi->password = Hash::make($updateData['password']);
        }

        $organisasi->save();

        return response([
            'message' => 'Berhasil update data organisasi',
            'data' => $organisasi
        ], 200);
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
