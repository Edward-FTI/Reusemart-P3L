<?php

namespace App\Http\Controllers\Api;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Barang;
use App\Models\Pegawai;
use App\Models\Penitip;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
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


    private function getOwner()
    {
        $user = Auth::user();
        // Cek apakah user ada dan role-nya 'owner'
        if ($user && isset($user->role) && strtolower($user->role) === 'owner') {
            return $user->id; // Atau return true jika hanya ingin cek owner
        }
        return null;
    }


    public function updateStatus(Request $request, string $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response(['message' => 'Data barang tidak ditemukan', 'data' => null], 404);
        }

        // Optional: Add authorization check if needed
        // $pegawaiId = $this->getPegawaiId();
        // if ($barang->id_pegawai !== $pegawaiId) {
        //     return response(['message' => 'Tidak diizinkan mengupdate status barang ini'], 403);
        // }

        // Validate only the 'status_barang' field
        $request->validate([
            'status_barang' => 'required|string',
        ]);

        $updateData = [
            'status_barang' => $request->status_barang,
        ];

        // If the status is 'transaksi_selesai', set tgl_pengambilan to current date/time
        if ($request->status_barang === 'transaksi_selesai') {
            $updateData['tgl_pengambilan'] = Carbon::now();
        }

        $barang->update($updateData);

        return response([
            'message' => 'Status barang berhasil diperbarui',
            'data' => $barang
        ], 200);
    }


    public function index()
    {
        $pegawaiId = $this->getPegawaiId();
        if (!$pegawaiId) {
            return response([
                'message' => 'Pegawai tidak ditemukan untuk user yang login'
            ], 404);
        }
        $barang = Barang::with(['penitip', 'kategori_barang', 'pegawai', 'hunter'])
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


    // BarangController.php
    public function indexOwner()
    {
        $user = Auth::user();
        if (!$user || strtolower($user->role) !== 'owner') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $barang = Barang::with(['penitip', 'kategori_barang', 'pegawai', 'hunter'])->get();

        return response()->json([
            'message' => 'Berhasil mengambil data barang',
            'data' => $barang,
        ]);
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
            'id_hunter' => 'nullable|integer',
            'tgl_penitipan' => 'required',
            'nama_barang' => 'required',
            'harga_barang' => 'required',
            'berat_barang' => 'required',
            'penambahan_durasi' => 'nullable|integer',
            'deskripsi' => 'required',
            'status_garansi' => 'nullable|date',
            // 'status_barang' => 'required',
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
        $storeData['status_barang'] = 'Dijual';
        $storeData['tgl_penitipan'] = Carbon::parse($storeData['tgl_penitipan'])->setTimeFromTimeString(now()->format('H:i:s'));

        $tglPenitipan = Carbon::parse($storeData['tgl_penitipan'])->copy()->addDays(30);
        $storeData['masa_penitipan'] = $tglPenitipan;

        $barang = Barang::create($storeData);

        // === TRIGGER NOTIFIKASI JIKA MASA PENITIPAN SISA 1 HARI ===
        try {
            $masaPenitipan = \Carbon\Carbon::parse($barang->masa_penitipan);
            $now = \Carbon\Carbon::now();
            $selisihHari = $now->diffInDays($masaPenitipan, false);

            if ($selisihHari === 1) {
                $penitip = Penitip::find($barang->id_penitip);
                if ($penitip) {
                    $user = User::where('email', $penitip->email)->first();
                    if ($user) {
                        $notificationService = app(\App\Services\NotificationService::class);
                        $notificationService->sendNotification(
                            $user->id,
                            'Masa Penitipan Hampir Habis',
                            'Masa penitipan untuk barang ' . $barang->nama_barang . ' akan habis besok. Segera ambil barang Anda!'
                        );
                        Log::info('Notifikasi penitipan hampir habis dikirim ke: ' . $user->email);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi masa penitipan: ' . $e->getMessage());
        }
        // === END NOTIFIKASI ===

        return response([
            'message' => 'Berhasil menambahkan data barang',
            'data' => $barang
        ], 201);
    }


    // public function store(Request $request)
    // {
    //     $pegawaiId = $this->getPegawaiId();
    //     if (!$pegawaiId) {
    //         return response([
    //             'message' => 'Pegawai tidak ditemukan untuk user yang login'
    //         ], 404);
    //     }

    //     $storeData = $request->all();
    //     $validate = Validator::make($storeData, [
    //         'id_penitip' => 'required',
    //         'id_kategori' => 'required',
    //         'id_hunter' => 'nullable|integer',
    //         'tgl_penitipan' => 'required',
    //         'nama_barang' => 'required',
    //         'harga_barang' => 'required',
    //         'berat_barang' => 'required',
    //         'penambahan_durasi' => 'nullable|integer',
    //         'deskripsi' => 'required',
    //         'status_garansi' => 'nullable|date',
    //         'tgl_pengambilan' => 'nullable|date',
    //         'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //         'gambar_dua' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     if ($validate->fails()) {
    //         return response([
    //             'message' => $validate->errors()
    //         ], 400);
    //     }

    //     if ($request->hasFile('gambar') && $request->hasFile('gambar_dua')) {
    //         $imageName = time() . '_' . uniqid() . '.' . $request->file('gambar')->extension();
    //         $imageName2 = time() . '_' . uniqid() . '.' . $request->file('gambar_dua')->extension();

    //         $path_gambar = 'images/barang/' . $imageName;
    //         $path_gambar2 = 'images/barang/' . $imageName2;

    //         $request->file('gambar')->move(public_path('images/barang'), $imageName);
    //         $request->file('gambar_dua')->move(public_path('images/barang'), $imageName2);
    //         $storeData['gambar'] = $path_gambar;
    //         $storeData['gambar_dua'] = $path_gambar2;
    //     }

    //     $storeData['id_pegawai'] = $pegawaiId;
    //     $storeData['status_barang'] = 'Dijual';
    //     $storeData['tgl_penitipan'] = Carbon::parse($storeData['tgl_penitipan'])->setTimeFromTimeString(now()->format('H:i:s'));

    //     // Simulasi masa penitipan hanya 2 menit dari waktu penitipan
    //     $tglPenitipan = Carbon::parse($storeData['tgl_penitipan'])->copy()->addMinutes(1);
    //     $storeData['masa_penitipan'] = $tglPenitipan;   

    //     $barang = Barang::create($storeData);

    //     // === CEK NOTIFIKASI 2 MENIT SETELAH PENITIPAN ===
    //     try {
    //         $masaPenitipan = Carbon::parse($barang->masa_penitipan);
    //         $now = Carbon::now();
    //         $selisihMenit = $masaPenitipan->diffInMinutes($now, false);

    //         if ($selisihMenit <= 1) {
    //             $penitip = Penitip::find($barang->id_penitip);
    //             if ($penitip) {
    //                 $user = User::where('email', $penitip->email)->first();
    //                 if ($user) {
    //                     $notificationService = app(\App\Services\NotificationService::class);
    //                     $notificationService->sendNotification(
    //                         $user->id,
    //                         'Masa Penitipan Hampir Habis',
    //                         'Masa penitipan untuk barang ' . $barang->nama_barang . ' telah berjalan selama 2 menit.'
    //                     );
    //                     Log::info('Notifikasi 2 menit penitipan dikirim ke: ' . $user->email);
    //                 }
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Gagal mengirim notifikasi 2 menit penitipan: ' . $e->getMessage());
    //     }
    //     // === END NOTIFIKASI ===

    //     return response([
    //         'message' => 'Berhasil menambahkan data barang',
    //         'data' => $barang
    //     ], 201);
    // }



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


    public function updatePublic(Request $request, string $id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response(['message' => 'Data tidak ditemukan', 'data' => null], 404);
        }

        $request->validate([
            'id_penitip' => 'required',
            'id_kategori' => 'required',
            'tgl_penitipan' => 'required|date',
            'nama_barang' => 'required',
            'harga_barang' => 'required',
            'berat_barang' => 'required',
            'penambahan_durasi' => 'nullable|integer',
            'deskripsi' => 'required',
            'status_garansi' => 'nullable|date',
            'status_barang' => 'required',
            'tgl_pengambilan' => 'nullable|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_dua' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path_gambar = $barang->gambar;
        $path_gambar2 = $barang->gambar_dua;

        if ($request->hasFile('gambar')) {
            if ($path_gambar && file_exists(public_path($path_gambar))) {
                unlink(public_path($path_gambar));
            }
            $imageName = time() . '_' . uniqid() . '.' . $request->file('gambar')->extension();
            $path_gambar = 'images/barang/' . $imageName;
            $request->file('gambar')->move(public_path('images/barang'), $imageName);
        }

        if ($request->hasFile('gambar_dua')) {
            if ($path_gambar2 && file_exists(public_path($path_gambar2))) {
                unlink(public_path($path_gambar2));
            }
            $imageName2 = time() . '_' . uniqid() . '.' . $request->file('gambar_dua')->extension();
            $path_gambar2 = 'images/barang/' . $imageName2;
            $request->file('gambar_dua')->move(public_path('images/barang'), $imageName2);
        }

        $barang->update([
            'id_penitip' => $request->id_penitip,
            'id_kategori' => $request->id_kategori,
            'tgl_penitipan' => Carbon::parse($request->tgl_penitipan)->setTimeFromTimeString(now()->format('H:i:s')),
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
            'id_hunter' => 'nullable|integer',
            'tgl_penitipan' => 'required',
            'nama_barang' => 'required',
            'harga_barang' => 'required',
            'berat_barang' => 'required',
            'penambahan_durasi' => 'nullable|integer',
            'deskripsi' => 'required',
            'status_garansi' => 'nullable|date',
            // 'status_barang' => 'required',
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
            'id_hunter' => $request->id_hunter,
            'tgl_penitipan' => $request->tgl_penitipan,
            'nama_barang' => $request->nama_barang,
            'harga_barang' => $request->harga_barang,
            'berat_barang' => $request->berat_barang,
            'penambahan_durasi' => $request->penambahan_durasi,
            'deskripsi' => $request->deskripsi,
            'status_garansi' => $request->status_garansi,
            // 'status_barang' => $request->status_barang,
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


    public function masaPenitipan($id)
    {
        try {
            $barang = Barang::select('id', 'masa_penitipan', 'id_penitip', 'nama_barang')->find($id);

            if (!$barang) {
                return response()->json([
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            }

            $tanggalKedaluwarsa = \Carbon\Carbon::parse($barang->masa_penitipan);
            $sisaMenit = now()->diffInMinutes($tanggalKedaluwarsa, false); // false: hasil bisa negatif jika lewat

            if ($sisaMenit === 3) {
                $penitip = Penitip::find($barang->id_penitip);
                $user = User::where('email', $penitip->email)->first();

                if ($user) {
                    $notificationService = app(NotificationService::class);
                    $notificationService->sendNotification(
                        $user->id,
                        'Masa Penitipan Hampir Habis',
                        'Masa penitipan untuk barang ' . $barang->nama_barang . ' akan habis dalam 3 menit.'
                    );
                    Log::info('Notifikasi dikirim ke: ' . $user->email);
                }
            }
            return response()->json([
                'message' => 'Cek masa penitipan berhasil',
                'sisa_menit' => $sisaMenit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses masa penitipan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
