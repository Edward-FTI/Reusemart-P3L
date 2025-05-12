<?php

namespace App\Http\Controllers\Api;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        if(count($jabatan) > 0) {
            return response([
                'message' => 'Berhasil mengambil data jabatan',
                'data' => $jabatan
            ], 200);
        }
        return response([
            'message' => 'Data jabatan kosong',
            'data' => null
        ], 400);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'role' => 'required',
        ]);

        if($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 404);
        }
        $jabatan = Jabatan::create($storeData);

        return response([
            'message' => 'Berhasil menambahkan data jabatan',
            'data' => $jabatan,
        ], 200);
    }


    public function show(string $id)
    {
        $jabatan = Jabatan::find($id);
        if(!is_null($jabatan)) {
            return response([
                'message' => 'Jabatan dengan id ' . $id . ' ditemukan',
                'data' => $jabatan
            ], 200);
        }
        return response([
            'message' => 'Jabatan tidak ditemukan',
            'data' => null
        ], 400);
    }


    public function update(Request $request, string $id)
    {
        $jabatan = Jabatan::find($id);
        if(is_null($jabatan)) {
            return response([
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateJabatan = $request->all();
        $validate = Validator::make($updateJabatan, [
            'role' => 'required'
        ]);

        if($validate->fails()) {
            return response([
                'message' => $validate->errors(),
            ], 400);
        }

        $jabatan->role = $updateJabatan['role'];

        if($jabatan->update($updateJabatan)) {
            return response([
                'message' => 'Berhasil update data jabatan',
                'data' => $jabatan
            ], 200);
        }
        return response([
            'message' => 'Gagal update data jabatan',
            'data' => null,
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::find($id);
        if(is_null($jabatan)) {
            return response([
                'message' => 'Data jabatan tidak ditemukan',
                'data' => null
            ], 404);
        }
        if($jabatan->delete()) {
            return response([
                'message' => 'Berhasil menghapus data jabatan',
                'data' => $jabatan,
            ], 200);
        }
    }
}
