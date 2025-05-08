<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index() {
        $pegawai = Pegawai::all();

        if(count($pegawai) > 0) {
            return response([
                'message' =>  'Berhasil mengambil data pegawai',
                'data' => $pegawai
            ], 200);
        }
        return response([
            'message' => 'Data Pegawai Kosong',
            'data' => null
        ], 400);
    }


    public function store(Request $request)
{
    $pegawai = Pegawai::create([
        'id_jabatan' => $request->id_jabatan,
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'gaji' => $request->gaji,
    ]);

    return response()->json($pegawai, 201);
}


    public function update(Request $request, string $id) {
        $pegawai = Pegawai::find($id);
        if(is_null($pegawai)) {
            return response([
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updatePegawai = $request->all();
        $validate = Validator::make($updatePegawai, [
            'id_jabatan' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'gaji' => 'required',
        ]);

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $pegawai->id_jabatan = $updatePegawai['id_jabatan'];
        $pegawai->nama = $updatePegawai['nama'];
        $pegawai->email = $updatePegawai['email'];
        $pegawai->gaji = $updatePegawai['gaji'];

        // if($pegawai->save)

    }
}
