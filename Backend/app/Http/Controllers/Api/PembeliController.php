<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;



class PembeliController extends Controller
{
    // GET semua pembeli
    public function index()
    {
        $pembelis = Pembeli::all();

        return response([
            'message' => $pembelis->isEmpty() ? 'Data pembeli kosong' : 'Berhasil mengambil data pembeli',
            'data' => $pembelis
        ], $pembelis->isEmpty() ? 404 : 200);

        $user = User::where('role', 'pembeli')->get();
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
            'nama_pembeli' => 'required|string|max:255',
            'email' => 'required|email|unique:pembelis,email',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8',
            'point' => 'integer',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $storeData['password'] = Hash::make($storeData['password']);

        $pembeli = Pembeli::create($storeData);

        return response([
            'message' => 'Berhasil menambahkan data pembeli',
            'data' => $pembeli
        ], 201);
    }

    // Endpoint kalau tidak terautentikasi
    public function ligon()
    {
        return response()->json("unauthenticated", 401);
    }

    // Register pembeli baru
    public function register(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8',

        ]);

        $pembeli = Pembeli::create([
            'nama_pembeli' => $request->nama_pembeli,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'point' => 0,
        ]);

        return response()->json([
            'pembeli' => $pembeli,
            'message' => 'Pembeli registered successfully',
        ], 201);
    }

    // Login pembeli
    // public function login(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|string|email',
    //             'password' => 'required|string',
    //         ]);

    //         $pembeli = Pembeli::where('email', $request->email)->first();

    //         if (!$pembeli || !Hash::check($request->password, $pembeli->password)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'status_code' => 401,
    //                 'message' => 'Login failed',
    //                 'error' => 'Invalid email or password'
    //             ], 401);
    //         }

    //         $token = $pembeli->createToken('auth_token')->plainTextToken;

    //         return response()->json([
    //             'success' => true,
    //             'status_code' => 200,
    //             'message' => 'Login success',
    //             'data' => [
    //                 'token' => $token,
    //                 'pembeli' => $pembeli
    //             ]
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'success' => false,
    //             'status_code' => 500,
    //             'message' => 'Failed to login',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }
    // }

    // Logout pembeli
    // public function logout(Request $request)
    // {
    //     if (Auth::check()) {
    //         $request->user()->currentAccessToken()->delete();
    //         return response()->json(['message' => 'Logged out successfully']);
    //     }

    //     return response()->json(['message' => 'Not logged in'], 401);
    // }

    // Tampilkan detail pembeli
    public function show($id)
    {
        $pembeli = Pembeli::find($id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli not found'], 404);
        }

        return response()->json($pembeli);
    }

    // Update pembeli
    public function update(Request $request, $id)
    {
        $pembeli = Pembeli::find($id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli not found'], 404);
        }

        $validatedData = $request->validate([
            'nama_pembeli' => 'string|max:255|nullable',
            'email' => 'required|email',
            'no_hp' => 'string|max:15|nullable',
            'password' => 'string|min:8|nullable',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $pembeli->update($validatedData);

        return response()->json([
            'pembeli' => $pembeli,
            'message' => 'Pembeli updated successfully',
        ]);
    }

    // Hapus pembeli
    public function destroy($id)
    {
        $pembeli = Pembeli::find($id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli not found'], 404);
        }

        $pembeli->delete();

        return response()->json(['message' => 'Pembeli deleted successfully']);
    }
}
