<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;

class BarangController extends Controller
{

    public function index()
    {
        $barang = Barang::with(['penitip', 'kategori_barang'])->get();
        if (count($barang) > 0) {
            return response([
                'message' => 'Berhasil mengambil data barang',
                'data' => $barang
            ], 200);
        }
        return response([
            'message' => 'Data Barang kosong',
            'data' => []
        ], 400);
    }


    // public function store(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'id_penitip' => 'required',
    //             'id_kategori' => 'required',
    //             'tgl_penitipan' => 'required',
    //             'nama_barang' => 'required',
    //             'harga_barang' => 'required',
    //             'deskripsi' => 'required',
    //             'status_garansi' => 'required',
    //             'status_barang' => 'required',
    //             'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //         ]);

    //         $path_gambar = null;
    //         if ($request->hasFile('gambar')) {
    //             $imageName = time() . '.' . $request->file('gambar')->extension();
    //             $path_gambar = 'images/barang/' . $imageName;
    //             $request->file('gambar')->move(public_path('images/barang'), $imageName);
    //         }

    //         $barang = Barang::create([
    //             'id_penitip' => $request->id_penitip,
    //             'id_kategori' => $request->id_kategori,
    //             'tgl_penitipan' => $request->tgl_penitipan,
    //             'nama_barang' => $request->nama_barang,
    //             'harga_barang' => $request->harga_barang,
    //             'deskripsi' => $request->deskripsi,
    //             'status_garansi' => $request->status_garansi,
    //             'status_barang' => $request->status_barang,
    //             'gambar' => $path_gambar,
    //         ]);

    //         return response([
    //             'message' => 'Berhasil menambahkan data barang',
    //             'data' => $barang
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response([
    //             'message' => 'Terjadi kesalahan saat menyimpan data barang.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }



    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_penitip' => 'required',
            'id_kategori' => 'required',
            'tgl_penitipan' => 'required',
            'nama_barang' => 'required',
            'harga_barang' => 'required',
            'deskripsi' => 'required',
            'status_garansi' => 'required',
            'status_barang' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $path_gambar = null;
        if ($request->hasFile('gambar')) {
            $imageName = time() . '.' . $request->file('gambar')->extension();
            $path_gambar = 'images/barang/' . $imageName;
            $request->file('gambar')->move(public_path('images/barang'), $imageName);
            $storeData['gambar'] = $path_gambar;
        }


        $barang = Barang::create($storeData);

        return response([
            'message' => 'Berhasil menambahkan data barang',
            'data' => $barang
        ], 200);
    }




    public function show(string $id)
    {
        $barang = Barang::find($id);
        if (!is_null($barang)) {
            return response([
                'message' => 'Barang dengan nama ' . $barang->nama_barang . ' ditemukan',
                'data' => $barang
            ], 200);
        }
        return response([
            'message' => 'Data barang tidak ditemukan',
            'data' => null
        ], 400);
    }



    public function update(Request $request, string $id)
    {
        $barang = Barang::find($id);
        if (is_null($barang)) {
            return response(['message' => 'Data tidak ditemukan', 'data' => null], 404);
        }

        $request->validate([
            'id_penitip' => 'required',
            'id_kategori' => 'required',
            'tgl_penitipan' => 'required',
            'nama_barang' => 'required',
            'harga_barang' => 'required',
            'deskripsi' => 'required',
            'status_garansi' => 'required',
            'status_barang' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path_gambar = $barang->gambar;
        if ($request->hasFile('gambar')) {
            if ($barang->gambar && file_exists(public_path($barang->gambar))) {
                unlink(public_path($barang->gambar));
            }

            $imageName = time() . '.' . $request->file('gambar')->extension();
            $path_gambar = 'images/barang/' . $imageName;
            $request->file('gambar')->move(public_path('images/barang'), $imageName);
        }
        if ($request->hasFile('gambar')) {
            if ($barang->gambar && file_exists(public_path($barang->gambar))) {
                unlink(public_path($barang->gambar));
            }

            $imageName = time() . '.' . $request->file('gambar')->extension();
            $path_gambar = 'images/barang/' . $imageName;
            $request->file('gambar')->move(public_path('images/barang'), $imageName);
        }


        $barang->update([
            'id_penitip' => $request->id_penitip,
            'id_kategori' => $request->id_kategori,
            'tgl_penitipan' => $request->tgl_penitipan,
            'nama_barang' => $request->nama_barang,
            'harga_barang' => $request->harga_barang,
            'deskripsi' => $request->deskripsi,
            'status_garansi' => $request->status_garansi,
            'status_barang' => $request->status_barang,
            'gambar' => $path_gambar,
        ]);

        return response([
            'message' => 'Berhasil update barang',
            'data' => $barang
        ], 200);
    }


    public function destroy(string $id)
    {
        $barang = Barang::find($id);
        if (is_null($barang)) {
            return response([
                'message' => 'Data barang tidak ditemukan',
                'data' => null
            ], 404);
        }
        if ($barang->gambar && file_exists(public_path($barang->gambar))) {
            unlink(public_path($barang->gambar));
        }
        if ($barang->delete()) {
            return response([
                'message' => ' Berhasil hapus data barang',
                'data' => $barang
            ], 200);
        }
    }
}
