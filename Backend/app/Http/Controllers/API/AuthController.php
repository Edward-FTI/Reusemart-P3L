<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Organisasi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function login(Request $request)
     {
         $loginData = $request->all();
         $validate = Validator::make($loginData, [
             'username' => 'required',
             'password' => 'required'
         ]);

         if ($validate->fails()) {
             return response(['message' => $validate->errors()], 400);
         }

         $user = null;
         $role = null;

         // Coba login sebagai Pegawai
         $pegawai = Pegawai::where('username', $loginData['username'])->first();
         if ($pegawai && $pegawai->password === $loginData['password']) {
             $user = $pegawai;
             $role = 'pegawai';
         }

         // Coba login sebagai Organisasi
         if (!$user) {
             $organisasi = Organisasi::where('username', $loginData['username'])->first();
             if ($organisasi && $organisasi->password === $loginData['password']) {
                 $user = $organisasi;
                 $role = 'organisasi';
             }
         }

         // Coba login sebagai Penitip
         if (!$user) {
             $penitip = Penitip::where('username', $loginData['username'])->first();
             if ($penitip && $penitip->password === $loginData['password']) {
                 $user = $penitip;
                 $role = 'penitip';
             }
         }

         // Coba login sebagai Pembeli
         if (!$user) {
             $pembeli = Pembeli::where('username', $loginData['username'])->first();
             if ($pembeli && $pembeli->password === $loginData['password']) {
                 $user = $pembeli;
                 $role = 'pembeli';
             }
         }

         // Gagal login di semua model
         if (!$user) {
             return response(['message' => 'Invalid Credential'], 401);
         }

         // Token authentication menggunakan Laravel Passport
         $token = $user->createToken('Authentication Token')->accessToken;

         return response([
             'message' => 'Authenticated',
             'user' => $user,
             'role' => $role,
             'token_type' => 'Bearer',
             'access_token' => $token
         ]);
     }

}
