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

    // Menyimpan data penitip baru
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_penitip' => 'required|string|max:255',
                'no_ktp' => 'required|string|min:16|max:20',
                'gambar_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'email' => 'required|email|unique:penitips,email',
                'password' => 'required|string|min:6',
                'badge' => 'required|string',
                'point' => 'required|integer',
                'saldo' => 'nullable|numeric',
            ]);

            $path_gambar = null;
            if ($request->hasFile('gambar_ktp')) {
                $imageName = time() . '.' . $request->file('gambar_ktp')->extension();
                $path_gambar = 'images/penitip/' . $imageName;
                $request->file('gambar_ktp')->move(public_path('images/penitip'), $imageName);
            }

            $penitip = Penitip::create([
                'nama_penitip' => $request->nama_penitip,
                'no_ktp' => $request->no_ktp,
                'gambar_ktp' => $path_gambar,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'badge' => $request->badge,
                'point' => $request->point,
                'saldo' => $request->saldo ?? 0
            ]);

            // Tambah ke tabel users
            User::create([
                'name' => $request->nama_penitip,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'penitip',
                'email_verified_at' => now(),
                'remember_token' => Str::random(60),
            ]);

            return response([
                'message' => 'Berhasil menambahkan data penitip',
                'data' => $penitip
            ], 201);
        } catch (\Exception $e) {
            return response([
                'message' => 'Terjadi kesalahan saat menyimpan data penitip.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response([
                'message' => 'Data penitip tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Penitip ditemukan',
            'data' => $penitip
        ], 200);
    }

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
            'no_ktp' => 'required|string|min:16|max:20',
            'gambar_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'email' => 'required|email|unique:penitips,email,' . $id,
            'password' => 'nullable|string|min:6',
            'badge' => 'required|string',
            'point' => 'required|integer',
            'saldo' => 'nullable|numeric',
        ]);

        // Gambar KTP
        if ($request->hasFile('gambar_ktp')) {
            if ($penitip->gambar_ktp && File::exists(public_path($penitip->gambar_ktp))) {
                File::delete(public_path($penitip->gambar_ktp));
            }
            $imageName = time() . '.' . $request->file('gambar_ktp')->extension();
            $path_gambar = 'images/penitip/' . $imageName;
            $request->file('gambar_ktp')->move(public_path('images/penitip'), $imageName);
            $penitip->gambar_ktp = $path_gambar;
        }

        $penitip->nama_penitip = $request->nama_penitip;
        $penitip->no_ktp = $request->no_ktp;
        $penitip->email = $request->email;
        $penitip->badge = $request->badge;
        $penitip->point = $request->point;
        $penitip->saldo = $request->saldo ?? $penitip->saldo;

        if (!empty($request->password)) {
            $penitip->password = Hash::make($request->password);
        }

        $penitip->nama_penitip = $request->nama_penitip;
        $penitip->no_ktp = $request->no_ktp;
        $penitip->email = $request->email;
        $penitip->badge = $request->badge;
        $penitip->point = $request->point;
        $penitip->saldo = $request->saldo ?? $penitip->saldo;

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
        return $this->show($id);
    }
}
