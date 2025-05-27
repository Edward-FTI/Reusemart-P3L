<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{

    private function getPegawaiId()
    {
        $userEmail = Auth::user()->email;
        $pegawai = Pegawai::where('email', $userEmail)->first();

        if (!$pegawai) {
            return null;
        }
        return $pegawai->id;
    }


    public function index()
    {
        $pegawaiId = $this->getPegawaiId();

        if (!$pegawaiId) {
            return response([
                'message' => 'Pegawai tidak ditemukan untuk user yang login'
            ], 404);
        }
        $barang = Barang::with(['penitip', 'kategori_barang'])
            ->where('id_pegawai', $pegawaiId)
            ->get();

        if ($barang->count() > 0) {
            return response([
                'message' => 'Berhasil mengambil data barang',
                'data' => $barang
            ], 200);
        }
        return response([
            'message' => 'Data Barang kosong',
            'data' => []
        ], 200);
    }


    public function indexPublic()
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
        $pegawaiId = $this->getPegawaiId();
        if (!$pegawaiId) {
            return response([
                'message' => 'Pegawai tidak ditemukan untuk user yang login'
            ], 404);
        }
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_penitip' => 'required',
            'id_kategori' => 'required',
            'tgl_penitipan' => 'required',
            'nama_barang' => 'required',
            'harga_barang' => 'required',
            'berat_barang' => 'required',
            'penambahan_durasi' => 'nullable|integer',
            'deskripsi' => 'required',
            'status_garansi' => 'required',
            'status_barang' => 'required',
            'tgl_pengambilan' => 'nullable|date',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_dua' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }
        if ($request->hasFile('gambar') && $request->hasFile('gambar_dua')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('gambar')->extension();
            $imageName2 = time() . '_' . uniqid() . '.' . $request->file('gambar_dua')->extension();

            $path_gambar = 'images/barang/' . $imageName;
            $path_gambar2 = 'images/barang/' . $imageName2;

            $request->file('gambar')->move(public_path('images/barang'), $imageName);
            $request->file('gambar_dua')->move(public_path('images/barang'), $imageName2);
            $storeData['gambar'] = $path_gambar;
            $storeData['gambar_dua'] = $path_gambar2;
        }
        $storeData['id_pegawai'] = $pegawaiId;
        $storeData['tgl_penitipan'] = Carbon::parse($storeData['tgl_penitipan'])->setTimeFromTimeString(now()->format('H:i:s'));

        $tglPenitipan = Carbon::parse($storeData['tgl_penitipan'])->copy()->addDays(30);
        $storeData['masa_penitipan'] = $tglPenitipan;

        $barang = Barang::create($storeData);

        return response([
            'message' => 'Berhasil menambahkan data barang',
            'data' => $barang
        ], 201);
    }


    public function show(string $id)
    {
        $pegawaiId = $this->getPegawaiId();
        $barang = Barang::find($id);

        if (is_null($barang)) {
            return response([
                'message' => 'Data barang tidak ditemukan',
                'data' => null
            ], 404);
        }
        if ($barang->id_pegawai !== $pegawaiId) {
            return response([
                'message' => 'Tidak diizinkan melihat barang ini',
                'data' => null
            ], 403);
        }
        return response([
            'message' => 'Barang dengan nama ' . $barang->nama_barang . ' ditemukan',
            'data' => $barang
        ], 200);
    }


    public function showPublic(string $id)
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
        $pegawaiId = $this->getPegawaiId();
        $barang = Barang::find($id);

        if (is_null($barang)) {
            return response(['message' => 'Data tidak ditemukan', 'data' => null], 404);
        }

        // Cek apakah barang dimiliki oleh pegawai yang login
        if ($barang->id_pegawai !== $pegawaiId) {
            return response(['message' => 'Tidak diizinkan mengedit barang ini'], 403);
        }
        $request->validate([
            'id_penitip' => 'required',
            'id_kategori' => 'required',
            'tgl_penitipan' => 'required',
            'nama_barang' => 'required',
            'harga_barang' => 'required',
            'berat_barang' => 'required',
            'penambahan_durasi' => 'nullable|integer',
            'deskripsi' => 'required',
            'status_garansi' => 'required',
            'status_barang' => 'required',
            'tgl_pengambilan' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_dua' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $path_gambar = $barang->gambar;
        $path_gambar2 = $barang->gambar_dua;

        if ($request->hasFile('gambar') && $request->hasFile('gambar_dua')) {
            if (file_exists(public_path($path_gambar))) unlink(public_path($path_gambar));
            if (file_exists(public_path($path_gambar2))) unlink(public_path($path_gambar2));

            $imageName = time() . '_' . uniqid() . '.' . $request->file('gambar')->extension();
            $imageName2 = time() . '_' . uniqid() . '.' . $request->file('gambar_dua')->extension();

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
            'berat_barang' => $request->berat_barang,
            'penambahan_durasi' => $request->penambahan_durasi,
            'deskripsi' => $request->deskripsi,
            'status_garansi' => $request->status_garansi,
            'status_barang' => $request->status_barang,
            'tgl_pengambilan' => $request->tgl_pengambilan,
            'gambar' => $path_gambar,
            'gambar_dua' => $path_gambar2,
        ]);
        return response([
            'message' => 'Berhasil update barang',
            'data' => $barang
        ], 200);
    }


    public function destroy(string $id)
    {
        $pegawaiId = $this->getPegawaiId();
        $barang = Barang::find($id);

        if (is_null($barang)) {
            return response([
                'message' => 'Data barang tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Cek apakah barang dimiliki oleh pegawai login
        if ($barang->id_pegawai !== $pegawaiId) {
            return response(['message' => 'Tidak diizinkan menghapus barang ini'], 403);
        }

        if ($barang->gambar && file_exists(public_path($barang->gambar))) {
            unlink(public_path($barang->gambar));
        }
        if ($barang->gambar_dua && file_exists(public_path($barang->gambar_dua))) {
            unlink(public_path($barang->gambar_dua));
        }
        if ($barang->delete()) {
            return response([
                'message' => 'Berhasil hapus data barang',
                'data' => $barang
            ], 200);
        }
        return response([
            'message' => 'Gagal menghapus data barang',
            'data' => null
        ], 500);
    }
}
