<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PenitipController extends Controller
{
    // Menampilkan semua data penitip
    public function index()
    {
        $penitips = Penitip::all();

        return response([
            'message' => $penitips->isEmpty() ? 'Data penitip kosong' : 'Berhasil mengambil data penitip',
            'data' => $penitips
        ], $penitips->isEmpty() ? 404 : 200);
    }

    // Menyimpan data penitip baru
    public function store(Request $request)
    {
        $data = $request->all();

        // Validasi input
        $validasi = Validator::make($data, [
            'nama_penitip' => 'required|string|max:255',
            'no_ktp' => 'required|string|min:16|max:20',
            'gambar_ktp' => 'nullable|string',
            'email' => 'required|email|unique:penitips,email',
            'password' => 'required|string|min:6',
            'badge' => 'required|string',
            'point' => 'required|integer',
            'saldo' => 'nullable|numeric',
        ]);

        if ($validasi->fails()) {
            return response(['message' => $validasi->errors()], 400);
        }

        // Menyimpan gambar KTP
        if ($request->hasFile('gambar_ktp')) {
            $namaGambar = time() . '.' . $request->file('gambar_ktp')->extension();
            $pathGambar = 'images/penitip/' . $namaGambar;
            $request->file('gambar_ktp')->move(public_path('images/penitip'), $namaGambar);
            $data['gambar_ktp'] = $pathGambar;
        }

        // Enkripsi password
        $data['password'] = Hash::make($data['password']);

        // Simpan ke tabel penitip
        $penitip = Penitip::create($data);

        // Buat juga akun user
        $user = new User();
        $user->name = $data['nama_penitip'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role = 'Penitip';
        $user->email_verified_at = now();
        $user->remember_token = Str::random(60);
        $user->save();

        return response([
            'message' => 'Berhasil menambahkan data penitip',
            'data' => $penitip
        ], 201);
    }

    // Mengubah data penitip
    public function update(Request $request, string $id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response(['message' => 'Data penitip tidak ditemukan', 'data' => null], 404);
        }

        $data = $request->all();

        $validasi = Validator::make($data, [
            'nama_penitip' => 'required|string|max:255',
            'no_ktp' => 'required|string|min:16|max:20',
            'gambar_ktp' => 'nullable|string',
            'email' => 'required|email|unique:penitips,email,' . $id,
            'password' => 'nullable|string|min:6',
            'badge' => 'required|string',
            'point' => 'required|integer',
            'saldo' => 'nullable|numeric',
        ]);

        if ($validasi->fails()) {
            return response(['message' => $validasi->errors()], 400);
        }

        // Jika ada gambar baru
        if ($request->hasFile('gambar_ktp')) {
            $namaGambar = time() . '.' . $request->file('gambar_ktp')->extension();
            $pathGambar = 'images/penitip/' . $namaGambar;
            $request->file('gambar_ktp')->move(public_path('images/penitip'), $namaGambar);
            $penitip->gambar_ktp = $pathGambar;
        }

        $penitip->nama_penitip = $data['nama_penitip'];
        $penitip->no_ktp = $data['no_ktp'];
        $penitip->email = $data['email'];
        $penitip->badge = $data['badge'];
        $penitip->point = $data['point'];
        $penitip->saldo = $data['saldo'] ?? $penitip->saldo;

        if (!empty($data['password'])) {
            $penitip->password = Hash::make($data['password']);
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

    // Menghapus data penitip
    public function destroy(string $id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response(['message' => 'Data penitip tidak ditemukan', 'data' => null], 404);
        }

        $penitip->delete();

        $user = User::where('email', $penitip->email)->first();
        if ($user) {
            $user->delete();
        }

        return response([
            'message' => 'Berhasil menghapus data penitip',
            'data' => $penitip
        ], 200);
    }

    // Menampilkan detail penitip berdasarkan ID
    public function show(string $id)
    {
        $penitip = Penitip::find($id);

        if ($penitip) {
            return response([
                'message' => 'Penitip ditemukan',
                'data' => $penitip
            ], 200);
        }

        return response([
            'message' => 'Data penitip tidak ditemukan',
            'data' => null
        ], 404);
    }

    // Cari penitip berdasarkan nama
    public function searchByName($name)
    {
        $penitips = Penitip::where('nama_penitip', 'LIKE', '%' . $name . '%')->get();

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
        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response([
                'message' => 'Penitip tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response([
            'message' => 'Penitip ditemukan',
            'data' => $penitip
        ], 200);
    }
}
