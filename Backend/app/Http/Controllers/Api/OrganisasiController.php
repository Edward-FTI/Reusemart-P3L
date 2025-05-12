<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OrganisasiController extends Controller
{
    // GET semua organisasi
    public function index()
    {
        return response()->json(Organisasi::all(), 200);
    }

    // Endpoint kalau tidak terautentikasi
    public function ligon()
    {
        return response()->json("unauthenticated", 401);
    }

    // Register organisasi baru
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:organisasis',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        $organisasi = Organisasi::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'organisasi' => $organisasi,
            'message' => 'Organisasi registered successfully',
        ], 201);
    }

    // Logout organisasi
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not logged in'], 401);
    }

    // Tampilkan detail organisasi
    public function show($id)
    {
        $organisasi = Organisasi::find($id);
        if (!$organisasi) {
            return response()->json(['message' => 'Organisasi not found'], 404);
        }

        return response()->json($organisasi);
    }

    // Update organisasi (termasuk permintaan)
    public function update(Request $request, $id)
    {
        $organisasi = Organisasi::find($id);
        if (!$organisasi) {
            return response()->json(['message' => 'Organisasi not found'], 404);
        }

        $validatedData = $request->validate([
            'nama' => 'string|max:255|nullable',
            'alamat' => 'string|max:255|nullable',
            'email' => 'required|email',
            'no_hp' => 'string|max:15|nullable',
            'password' => 'string|min:8|nullable',
            'permintaan' => 'string|nullable',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $organisasi->update($validatedData);

        return response()->json([
            'organisasi' => $organisasi,
            'message' => 'Organisasi updated successfully',
        ]);
    }

    // Hapus organisasi
    public function destroy($id)
    {
        $organisasi = Organisasi::find($id);
        if (!$organisasi) {
            return response()->json(['message' => 'Organisasi not found'], 404);
        }

        $organisasi->delete();

        return response()->json(['message' => 'Organisasi deleted successfully']);
    }
}
