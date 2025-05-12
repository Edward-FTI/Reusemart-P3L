<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
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


   public function registerOrg(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:organisasis,email|unique:users,email',
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

    $user = User::create([
        'name' => $request->nama,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'Organisasi',
    ]);

    return response()->json([
        'organisasi' => $organisasi,
        'user' => $user,
        'message' => 'Organisasi registered successfully',
    ], 201);
}

public function update(Request $request, string $id)
{
    $organisasi = Organisasi::find($id);
    if (!$organisasi) {
        return response()->json(['message' => 'Organisasi not found'], 404);
    }

    $validate = Validator::make($request->all(), [
        'nama' => 'string|max:255|nullable',
        'alamat' => 'string|max:255|nullable',
        'email' => 'required|email',
        'no_hp' => 'string|max:15|nullable',
        'password' => 'string|min:8|nullable',
        'permintaan' => 'string|nullable',
    ]);

    if ($validate->fails()) {
        return response(['message' => $validate->errors()], 400);
    }

    $validatedData = $validate->validated();

    $organisasi->nama = $validatedData['nama'] ?? $organisasi->nama;
    $organisasi->alamat = $validatedData['alamat'] ?? $organisasi->alamat;
    $organisasi->email = $validatedData['email'] ?? $organisasi->email;
    $organisasi->no_hp = $validatedData['no_hp'] ?? $organisasi->no_hp;
    $organisasi->permintaan = $validatedData['permintaan'] ?? $organisasi->permintaan;

    if (!empty($validatedData['password'])) {
        $organisasi->password = Hash::make($validatedData['password']);
    }

    $organisasi->save();

    // Update data user juga
    $user = User::where('email', $organisasi->email)->first();
    if ($user) {
        if (!empty($validatedData['nama'])) {
            $user->name = $validatedData['nama'];
        }
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();
    }

    return response()->json([
        'organisasi' => $organisasi,
        'user' => $user,
        'message' => 'Organisasi updated successfully',
    ]);
}


public function destroy(string $id)
{
    $organisasi = Organisasi::find($id);
    if (!$organisasi) {
        return response(['message' => 'Data organisasi tidak ditemukan', 'data' => null], 404);
    }

    // Cari user sebelum hapus organisasi
    $user = User::where('email', $organisasi->email)->first();

    $organisasi->delete();

    if ($user) {
        $user->delete();
    }

    return response([
        'message' => 'Berhasil menghapus data organisasi dan user terkait',
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