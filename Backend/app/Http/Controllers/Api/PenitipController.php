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
use Illuminate\Support\Facades\Auth;

class PenitipController extends Controller
{

    private function getPenitipId()
    {
        try {
            $user = Auth::user();
            if (!$user || !isset($user->email)) {
                return response()->json(['message' => 'User belum login atau tidak memiliki email'], 401);
            }

            $penitip = Penitip::where('email', $user->email)->first();
            if (!$penitip) {
                return response()->json(['message' => 'Penitip tidak ditemukan untuk email tersebut'], 404);
            }

            return $penitip->id;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil ID penitip', 'error' => $e->getMessage()], 500);
        }
    }

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
            'alamat' => 'required|string|max:255',
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
        $data['nominalTarik'] = 500000;

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
            'no_ktp' => 'required|string|min:16|max:20|unique:penitips,no_ktp,' . $id,
            'gambar_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'alamat' => 'required|string|max:255',
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
        $penitip->alamat = $data['alamat'];
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

    public function getPenitipData()
    {
        try {
            $user = Auth::user();
            if (!$user || !isset($user->email)) {
                return response()->json(['message' => 'User belum login atau tidak memiliki email'], 401);
            }

            $penitip = Penitip::where('email', $user->email)->first();
            if (!$penitip) {
                return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
            }

            return response()->json([
                'id' => $penitip->id,
                'nama_penitip' => $penitip->nama_penitip,
                'no_ktp' => $penitip->no_ktp,
                'alamat' => $penitip->alamat,
                'saldo' => $penitip->saldo,
                'point' => $penitip->point,
                'email' => $penitip->email,
                'badge' => $penitip->badge,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil data penitip', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllPenitipIds()
    {
        try {
            $penitips = Penitip::select('id', 'nama_penitip')->get();

            if ($penitips->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data penitip',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'message' => 'Berhasil mengambil seluruh data penitip',
                'data' => $penitips
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data penitip',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function tarikSaldo(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
        ]);

        $penitip = Penitip::findOrFail($id);

        if ($request->jumlah > $penitip->saldo) {
            return response()->json(['message' => 'Saldo tidak mencukupi'], 400);
        }

        $penitip->saldo -= $request->jumlah;
        $penitip->save();

        return response()->json([
            'message' => 'Saldo berhasil ditarik',
            'data' => $penitip
        ]);
    }

    // public function tarikSaldo(Request $request, $id)
    // {
    //     $request->validate([
    //         'jumlah' => 'required|numeric|min:1',
    //     ]);

    //     $penitip = Penitip::findOrFail($id);
    //     $jumlah = $request->jumlah;
    //     $biaya = $jumlah * 0.05;
    //     $total = $jumlah + $biaya;

    //     if ($jumlah < $penitip->nominalTarik) {
    //         return response()->json(['message' => 'Jumlah kurang dari nominal penarikan minimal'], 400);
    //     }

    //     if ($total > $penitip->saldo) {
    //         return response()->json(['message' => 'Saldo tidak mencukupi'], 400);
    //     }

    //     $penitip->saldo -= $total;
    //     $penitip->save();

    //     return response()->json([
    //         'message' => "Berhasil menarik Rp $jumlah + Rp $biaya (biaya 5%)",
    //         'data' => $penitip
    //     ]);
    // }





    // Menampilkan data penitip berdasarkan ID
}
