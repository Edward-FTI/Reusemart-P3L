<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\JabatanController;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\CssSelector\Node\FunctionNode;
class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with('jabatan')->get();

                if (count($pegawai) > 0) {
            return response([
                'message' =>  'Berhasil mengambil data pegawai',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Data pegawai kosong',
            'data' => []
        ], 400);
    }


    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_jabatan' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gaji' => 'required',
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $pegawai = Pegawai::create($storeData);

        $jabatan = Jabatan::find($storeData['id_jabatan']);

        if (!$jabatan) {
        return response(['message' => 'Jabatan tidak ditemukan'], 404);
    }

        $user = new User();
        $user->name = $storeData['nama'];
        $user->email = $storeData['email'];
        $user->password = bcrypt($storeData['password']);
        $user->role = $jabatan->role;
        $user->save();

        return response([
            'message' => 'Berhasil menambahkan data pegawai',
            'data' => $pegawai
        ], 200);
    }


    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::find($id);
        if (is_null($pegawai)) {
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
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }
        $pegawai->id_jabatan = $updatePegawai['id_jabatan'];
        $pegawai->nama = $updatePegawai['nama'];
        $pegawai->email = $updatePegawai['email'];
        $pegawai->gaji = $updatePegawai['gaji'];

        // update()
        $user = User::where('email', $pegawai->email)->first();
        if ($user) {
            $user->name = $updatePegawai['nama'];
            $user->email = $updatePegawai['email'];
            $user->role = Jabatan::find($updatePegawai['id_jabatan'])->role;

            $user->save();
        }


        if ($pegawai->update($updatePegawai)) {
            return response([
                'message' => 'Berhasil update data pegawai',
                'data' => $pegawai
            ], 200);
        }
        return response([
            'message' => 'Gagal upadate data pegawai',
            'data' => null
        ], 400);
    }


    public function destroy(string $id)
    {
        $pegawai = Pegawai::find($id);
        if (is_null($pegawai)) {
            return response([
                'message' => 'Data pegawai tidak ditemukan',
                'data' => null
            ], 400);
        }
        $user = User::where('email', $pegawai->email)->first();
        if ($user) {
            $user->delete();
        }
        if ($pegawai->delete()) {
            return response([
                'message' => 'Berhasil menghapus data pegawai',
                'data' => $pegawai
            ], 200);
        }
    }


    // publick function search(Request $request) {
    //     $search = $request->search();

    //     $pegawai =
    // }


    public function show(string $id)
    {
        $pegawai = Pegawai::find($id);

        if (!is_null($pegawai)) {
            return response([
                'message' => 'Pegawai dengan nama ' . $pegawai->nama . ' ditemukan',
                'data' => $pegawai,
            ], 200);
        }
        return response([
            'message' => 'Data tidak ditemukan',
            'data' => null
        ], 400);
    }


    public function searchByName($name)
    {
        try {
            $pegawai = Pegawai::where('nama', 'LIKE', '%' . $name . '%')->get();
            if ($pegawai->isEmpty()) {
                throw new \Exception('Pegawai dengan nama ' . $pegawai . ' tidak ditemukan');
            }
            return response([
                'status' => true,
                'message' => 'Berhasil mengambil data pegawai',
                'data' => $pegawai,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }


    public function searchByJabatan($jabatan)
    {
        try {
            $pegawai = Pegawai::where('id_jabatan', $jabatan)->get();

            if ($pegawai->isEmpty()) {
                throw new \Exception('pegawai dengan jabatan ' . $jabatan . ' tidak ditemukan');
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil data pegawai',
                'data' => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }


    public function searchById($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menemukan pegawai',
                'data' => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 404);
        }
    }
}
