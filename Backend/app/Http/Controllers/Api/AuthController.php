<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login a user and return an authentication token.
     */
    public function login(Request $request)
    {
        // Validasi input
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek jika validasi gagal
        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors(),
            ], 400);
        }

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ditemukan dan password valid
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Buat token autentikasi
        $token = $user->createToken('auth_token')->plainTextToken;

        // Menyertakan data pengguna dan role
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Register a new user.
     */
    // public function register(Request $request)
    // {
    //     // Validasi input
    //     $validate = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users',
    //         'username' => 'required|string|unique:users',
    //         'password' => 'required|string|min:6',
    //         'role' => 'required|string|in:admin,pegawai-gudang,customer-service', // Menambahkan validasi role
    //     ]);

    //     // Cek jika validasi gagal
    //     if ($validate->fails()) {
    //         return response()->json([
    //             'message' => $validate->errors(),
    //         ], 400);
    //     }

    //     // Membuat user baru
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'username' => $request->username,
    //         'password' => bcrypt($request->password),
    //         'role' => $request->role,
    //     ]);

    //     return response()->json([
    //         'message' => 'User registered successfully',
    //         'user' => $user,
    //     ], 201);
    // }

    /**
     * Logout the current user and revoke their token.
     */
    public function logout(Request $request)
    {
        // Menghapus token yang sedang aktif
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function updateFcmToken(Request $request, $id)
    {
        try {
            $request->validate([
                'fcm_token' => 'required',
            ]);

            $user = User::findOrFail($id);

            if ($user) {
                $user->fcm_token = $request->input('fcm_token');
                $user->save();
                return response()->json([
                    'success' => true,
                    'status_code' => 200,
                    'message' => 'FCM ID Agent updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'error' => 'User not found',
                    'message' => 'User not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Failed to update fcm token ',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
