<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PenitipController extends Controller
{
    // Menampilkan semua data penitip
    public function index()
    {
        $penitips = Penitip::all();

        $users = User::where('role', 'Penitip')->get();
        if ($users->isEmpty()) {
            return response([
                'message' => 'Data penitip kosong',
                'data' => []
            ], 404);
        }


        if ($penitips->isEmpty()) {
            return response([
                'message' => 'Data penitip kosong',
                'data' => []
            ], 404);
        }

        return response([
            'message' => 'Berhasil mengambil data penitip',
            'data' => $penitips
        ], 200);
    }


    public function store(Request $request)
{
    $data = $request->all();

    // Validate input
    $validasi = Validator::make($data, [
        'nama_penitip' => 'required|string|max:255',
        'no_ktp' => 'required|string|min:16|max:20',
        'gambar_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'email' => 'required|email|unique:penitips,email',
        'password' => 'required|string|min:6',
        'badge' => 'required|string',
        'point' => 'required|integer',
        'saldo' => 'nullable|numeric',
    ]);

    if ($validasi->fails()) {
        return response(['message' => $validasi->errors()], 400);
    }

    // Handle file upload for gambar_ktp
    if ($request->hasFile('gambar_ktp')) {
        $gambarFile = $request->file('gambar_ktp');
        $namaGambar = time() . '.' . $gambarFile->extension();
        $pathGambar = 'images/penitip/' . $namaGambar;
        $gambarFile->move(public_path('images/penitip'), $namaGambar);
        $data['gambar_ktp'] = $pathGambar;
    }

    // Encrypt password
    $data['password'] = Hash::make($data['password']);

    // Create penitip
    $penitip = Penitip::create($data);

    // Create user for penitip as well
    $user = new User();
    $user->name = $data['nama_penitip'];
    $user->email = $data['email'];
    $user->password = $data['password'];
    $user->role = 'Penitip';
    $user->email_verified_at = now();
    $user->remember_token = Str::random(60);
    $user->save();

    return response([
        'message' => 'Penitip successfully created',
        'data' => $penitip,
    ], 201);
}

// public function store(Request $request)
// {
//     try {
//         // Validasi input
//         $validator = Validator::make($request->all(), [
//             'nama_penitip' => 'required|string|max:255',
//             'no_ktp' => 'required|string|min:16|max:20|unique:penitips,no_ktp',
//             'gambar_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
//             'email' => 'required|email|unique:penitips,email|unique:users,email',
//             'password' => 'required|string|min:6',
//             'badge' => 'required|string',
//             'point' => 'required|integer',
//             'saldo' => 'nullable|numeric',
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'message' => 'Validasi gagal',
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         // Simpan gambar KTP
//         $pathGambar = null;
//         if ($request->hasFile('gambar_ktp')) {
//             $namaGambar = time() . '.' . $request->file('gambar_ktp')->extension();
//             $pathGambar = 'images/penitip/' . $namaGambar;
//             $request->file('gambar_ktp')->move(public_path('images/penitip'), $namaGambar);
//         }

//         // Simpan ke tabel penitip
//         $penitip = Penitip::create([
//             'nama_penitip' => $request->nama_penitip,
//             'no_ktp' => $request->no_ktp,
//             'gambar_ktp' => $pathGambar,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             'badge' => $request->badge ?? '',
//             'point' => $request->point ?? 0,
//             'saldo' => $request->saldo ?? 0,
//         ]);

//         // Simpan juga ke tabel user
//         $user = User::create([
//             'name' => $request->nama_penitip,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             'role' => 'Penitip',
//             'email_verified_at' => now(),
//             'remember_token' => Str::random(60),
//         ]);

//         return response()->json([
//             'message' => 'Berhasil menambahkan data penitip',
//             'data' => $penitip
//         ], 201);

//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'Gagal menambahkan data penitip',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }




    public function update(Request $request, $id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response([
                'message' => 'Data penitip tidak ditemukan',
                'data' => null
            ], 404);
        }

        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'no_ktp' => 'required|string|min:16|max:20|unique:penitips,no_ktp,' .$id,
            'gambar_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'email' => 'required|email|unique:penitips,email,' . $id,
            'password' => 'nullable|string|min:6',
            'badge' => 'required|string',
            'point' => 'required|integer',
            'saldo' => 'nullable|numeric',
        ]);

        // Gambar KTP
        if ($request->hasFile('gambar_ktp')) {
            $namaGambar = time() . '.' . $request->file('gambar_ktp')->extension();
            $pathGambar = 'images/penitip/' . $namaGambar;
            $request->file('gambar_ktp')->move(public_path('images/penitip'), $namaGambar);
            $data['gambar_ktp'] = $pathGambar;
        }

        $penitip->nama_penitip = $data['nama_penitip'];
        $penitip->no_ktp = $data['no_ktp'];
        $penitip->gambar_ktp = $data['gambar_ktp'] ?? $penitip->gambar_ktp;
        $penitip->email = $data['email'];
        $penitip->badge = $data['badge'];
        $penitip->point = $data['point'];
        $penitip->saldo = $data['saldo'] ?? $penitip->saldo;

        if (!empty($request->password)) {
            $penitip->password = Hash::make($request->password);
        }
        $penitip->update($data);

        // Update juga data user
        $user = User::where('email', $penitip->email)->first();
        if ($user) {
            $user->name = $penitip->nama_penitip;
            $user->email = $penitip->email;
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->role = 'Penitip';
            $user->email_verified_at = now();
            $user->remember_token = Str::random(60);
            $user->save();
        }

        return response([
            'message' => 'Berhasil memperbarui data penitip',
            'data' => $penitip
        ], 200);
    }

    public function destroy($id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response([
                'message' => 'Data penitip tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Hapus gambar
        if ($penitip->gambar_ktp && File::exists(public_path($penitip->gambar_ktp))) {
            File::delete(public_path($penitip->gambar_ktp));
        }

        // Hapus user
        $user = User::where('email', $penitip->email)->first();
        if ($user) {
            $user->delete();
        }

        $penitip->delete();

        return response([
            'message' => 'Berhasil menghapus data penitip',
            'data' => $penitip
        ], 200);
    }

    public function searchByName($name)
    {
        $penitips = Penitip::where('nama_penitip', 'like', '%' . $name . '%')->get();

        if ($penitips->isEmpty()) {
            return response([
                'message' => 'Penitip dengan nama tersebut tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response([
            'message' => 'Berhasil mengambil data penitip',
            'data' => $penitips
        ], 200);
    }

    // Cari penitip berdasarkan ID
    public function searchById($id)
    {
        return $this->index($id);
    }
    public function searchByNameAndId($name, $id)
    {
        $penitip = Penitip::where('nama_penitip', 'like', '%' . $name . '%')->where('id', $id)->first();

        if (!$penitip) {
            return response([
                'message' => 'Penitip dengan nama dan ID tersebut tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Berhasil mengambil data penitip',
            'data' => $penitip
        ], 200);
    }

    // Menampilkan data penitip berdasarkan ID

}
