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
            'gambar_dua' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }
        $path_gambar = null;
        $path_gambar2 = null;
        if ($request->hasFile('gambar') && $request->hasFile('gambar_dua')) {
            $imageName = time() . '.' . $request->file('gambar')->extension();
            $imageName2 = time() . '.' . $request->file('gambar_dua')->extension();

            $path_gambar = 'images/barang/' . $imageName;
            $path_gambar2 = 'images/barang/' . $imageName2;

            $request->file('gambar')->move(public_path('images/barang'), $imageName);
            $request->file('gambar_dua')->move(public_path('images/barang'), $imageName2);

            $storeData['gambar'] = $path_gambar;
            $storeData['gambar_dua'] = $path_gambar2;
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
            'gambar_dua' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path_gambar = $barang->gambar;
        $path_gambar2 = $barang->gambar;
        if ($request->hasFile('gambar') && $request->hasFile('gambar_dua')) {
            if (($barang->gambar && file_exists(public_path($barang->gambar))) && ($barang->gambar_dua && file_exists(public_path($barang->gambar_dua))) ) {
                unlink(public_path($barang->gambar));
                unlink(public_path($barang->gambar_dua));
            }
            $imageName = time() . '.' . $request->file('gambar')->extension();
            $imageName2 = time() . '.' . $request->file('gambar_dua')->extension();

            $path_gambar = 'images/barang/' . $imageName;
            $path_gambar2 = 'images/barang/' . $imageName2;

            $request->file('gambar')->move(public_path('images/barang'), $imageName);
            $request->file('gambar_dua')->move(public_path('images/barang'), $imageName2);
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
            'gambar2' => $path_gambar2,
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
        if ( ($barang->gambar && file_exists(public_path($barang->gambar))) && ($barang->gambar_dua && file_exists(public_path($barang->gambar_dua)))  ) {
            unlink(public_path($barang->gambar));
            unlink(public_path($barang->gambar_dua));
        }
        if ($barang->delete()) {
            return response([
                'message' => ' Berhasil hapus data barang', 
                'data' => $barang
            ], 200);
        }
    }
}
