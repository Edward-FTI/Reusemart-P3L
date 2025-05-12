<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class PembeliController extends Controller
{
    // GET semua pembeli
    public function index()
    {
        return response()->json(Pembeli::all(), 200);
    }

    // Endpoint kalau tidak terautentikasi
    public function ligon()
    {
        return response()->json("unauthenticated", 401);
    }

    // Register pembeli baru
    public function register(Request $request)
{
    $validate = Validator::make($request->all(), [
        'nama_pembeli' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email|unique:pembelis,email',
        'no_hp' => 'required|string|max:15',
        'password' => 'required|string|min:8',
    ]);

    if ($validate->fails()) {
        return response()->json([
            'message' => $validate->errors(),
        ], 400);
    }

    $pembeli = Pembeli::create([
        'nama_pembeli' => $request->nama_pembeli,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
        'password' => Hash::make($request->password),
        'point' => 0,
    ]);

    $user = new \App\Models\User();
    $user->name = $request->nama_pembeli;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role = 'Pembeli'; 
    $user->save();

    return response()->json([
        'pembeli' => $pembeli,
        'user' => $user,
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
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not logged in'], 401);
    }

    // Tampilkan detail pembeli
    public function show($id)
    {
        $pembeli = Pembeli::find($id);
        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli not found'], 404);
        }

        return response()->json($pembeli);
    }

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

    
    $user = \App\Models\User::where('email', $pembeli->email)->first();
    if ($user) {
        if (!empty($validatedData['nama_pembeli'])) {
            $user->name = $validatedData['nama_pembeli'];
        }
        $user->email = $validatedData['email'];

        if (isset($request->password) && !empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
    }

    return response()->json([
        'pembeli' => $pembeli,
        'user' => $user,
        'message' => 'Pembeli updated successfully',
    ]);
}

public function destroy($id)
{
    $pembeli = Pembeli::find($id);
    if (!$pembeli) {
        return response()->json(['message' => 'Pembeli not found'], 404);
    }

    // Cari user berdasarkan email sebelum hapus pembeli
    $user = \App\Models\User::where('email', $pembeli->email)->first();

    // Hapus pembeli
    $pembeli->delete();

    // Hapus user kalau ada
    if ($user) {
        $user->delete();
    }

    return response()->json(['message' => 'Pembeli and associated user deleted successfully']);
}


}
