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
    public function index()
    {
        $penitips = Penitip::all();

        return response([
            'message' => $penitips->isEmpty() ? 'Data penitip kosong' : 'Berhasil mengambil data penitip',
            'data' => $penitips
        ], $penitips->isEmpty() ? 404 : 200);

        $user = User::where('role', 'penitip')->get();
        if ($user->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mengambil data User',
                'data' => $user
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_penitip' => 'required|string|max:255',
            'no_ktp' => 'required|string|min:16|max:20',
            'gambar_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'email' => 'required|email|unique:penitips,email',
            'password' => 'required|string|min:6',
            'badge' => 'required|string',
            'point' => 'required|integer',
            'saldo' => 'nullable|numeric',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $pathGambar = null;
        if ($request->hasFile('gambar_ktp')) {
            $imageName = time() . '.' . $request->file('gambar_ktp')->extension();
            $path_gambar = 'images/penitip/' . $imageName;
            $request->file('gambar_ktp')->move(public_path('images/penitip'), $imageName);

            $storeData['gambar_ktp'] = $path_gambar;
        }

        $storeData['password'] = Hash::make($storeData['password']);
        $penitip = Penitip::create($storeData);

        $user = new User();
        $user->name = $storeData['nama_penitip'];
        $user->email = $storeData['email'];
        $user->password = $storeData['password'];
        $user->role = 'penitip';
        $user->email_verified_at = now();
        $user->remember_token = Str::random(60);
        $user->save();

        return response([
            'message' => 'Berhasil menambahkan data penitip',
            'data' => $penitip
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response(['message' => 'Data penitip tidak ditemukan', 'data' => null], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama_penitip' => 'required|string|max:255',
            'no_ktp' => 'required|string|min:16|max:20',
            'gambar_ktp' => 'nullable|string',
            'email' => 'required|email|unique:penitips,email,' . $id,
            'password' => 'nullable|string|min:6',
            'badge' => 'required|string',
            'point' => 'required|integer',
            'saldo' => 'nullable|numeric',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $penitip->nama_penitip = $updateData['nama_penitip'];
        $penitip->no_ktp = $updateData['no_ktp'];
        $penitip->gambar_ktp = $updateData['gambar_ktp'] ?? $penitip->gambar_ktp;
        $penitip->email = $updateData['email'];
        $penitip->badge = $updateData['badge'];
        $penitip->point = $updateData['point'];
        $penitip->saldo = $updateData['saldo'] ?? $penitip->saldo;

        if (!empty($updateData['password'])) {
            $penitip->password = Hash::make($updateData['password']);
        }

        $penitip->save();

        $user = User::where('email', $penitip->email)->first();
        if ($user) {
            $user->name = $penitip->nama_penitip;
            $user->email = $penitip->email;
            if (!empty($updateData['password'])) {
                $user->password = Hash::make($updateData['password']);
            }
            $user->role = 'penitip';
            $user->email_verified_at = now();
            $user->remember_token = Str::random(60);
            $user->save();
        }

        return response([
            'message' => 'Berhasil update data penitip',
            'data' => $penitip
        ], 200);
    }

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

        return response([
            'message' => 'Berhasil menghapus data penitip',
            'data' => $penitip
        ], 200);
    }

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
            'message' => 'Data tidak ditemukan',
            'data' => null
        ], 404);
    }

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
