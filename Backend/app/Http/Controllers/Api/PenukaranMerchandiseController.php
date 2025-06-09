<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenukaranMerchandise;
use App\Models\Merchandise;
use App\Models\Pembeli;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenukaranMerchandiseController extends Controller
{
    // ===============================
    // Akses Public (Tanpa Login)
    // ===============================
    public function indexPublic()
    {
        $penukaran = PenukaranMerchandise::with(['pembeli', 'pegawai', 'merchandise'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua penukaran merchandise (public)',
            'data' => $penukaran
        ]);
    }

    // ===============================
    // Akses Login (Pembeli atau Pegawai via Email)
    // ===============================
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User belum login'
            ], 401);
        }

        $email = $user->email;

        // Cari pembeli/pegawai berdasarkan email user
        $pembeli = Pembeli::where('email', $email)->first();
        $pegawai = Pegawai::where('email', $email)->first();

        if ($pembeli) {
            $penukaran = PenukaranMerchandise::where('id_pembeli', $pembeli->id)
                ->with(['merchandise', 'pegawai.user'])
                ->get();
        } elseif ($pegawai) {
            $penukaran = PenukaranMerchandise::where('id_pegawai', $pegawai->id)
                ->with(['merchandise', 'pembeli.user'])
                ->get();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terdaftar sebagai pembeli atau pegawai'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar penukaran merchandise berdasarkan email login',
            'data' => $penukaran
        ]);
    }

    // ===============================
    // Penukaran baru (login dibutuhkan)
    // ===============================
    // public function store(Request $request)
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User belum login'
    //         ], 401);
    //     }

    //     $email = $user->email;
    //     $pembeli = Pembeli::where('email', $email)->first();
    //     $pegawai = Pegawai::where('email', $email)->first();

    //     if (!$pembeli && !$pegawai) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User tidak terdaftar sebagai pembeli atau pegawai'
    //         ], 403);
    //     }

    //     // Validasi umum
    //     $rules = [
    //         'id_merchandise' => 'required|exists:merchandises,id',
    //         'jumlah' => 'required|integer|min:1'
    //     ];

    //     // Jika pegawai, harus input id_pembeli
    //     if ($pegawai) {
    //         $rules['id_pembeli'] = 'required|exists:pembelis,id';
    //     }

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validasi gagal',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     // Tentukan pembeli yang digunakan (dari email jika pembeli, dari input jika pegawai)
    //     $selectedPembeli = $pembeli ?? Pembeli::find($request->id_pembeli);
    //     $merchandise = Merchandise::find($request->id_merchandise);

    //     $totalPoin = $merchandise->nilai_point * $request->jumlah;

    //     if ($selectedPembeli->point < $totalPoin) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Poin pembeli tidak mencukupi'
    //         ], 400);
    //     }

    //     if ($merchandise->jumlah < $request->jumlah) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Stok merchandise tidak mencukupi'
    //         ], 400);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $merchandise->jumlah -= $request->jumlah;
    //         $merchandise->save();

    //         $selectedPembeli->point -= $totalPoin;
    //         $selectedPembeli->save();

    //         $penukaran = PenukaranMerchandise::create([
    //             'id_pembeli' => $selectedPembeli->id,
    //             'id_merchandise' => $merchandise->id,
    //             'id_pegawai' => $pegawai ? $pegawai->id : null, // Pegawai opsional
    //             'tanggal_penukaran' => now(),
    //             'jumlah' => $request->jumlah,
    //             'status' => 'selesai',
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Penukaran merchandise berhasil',
    //             'data' => $penukaran
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal melakukan penukaran',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User belum login'], 401);
        }

        $email = $user->email;
        $pembeli = Pembeli::where('email', $email)->first();
        $pegawai = Pegawai::where('email', $email)->first();

        if (!$pembeli && !$pegawai) {
            return response()->json(['success' => false, 'message' => 'User tidak terdaftar'], 403);
        }

        $items = $request->items;
        if (!is_array($items) || count($items) == 0) {
            return response()->json(['success' => false, 'message' => 'Item harus berupa array dan tidak boleh kosong'], 422);
        }

        $selectedPembeli = $pembeli ?? Pembeli::find($request->id_pembeli);

        DB::beginTransaction();
        try {
            $totalPoin = 0;

            foreach ($items as $item) {
                $merch = Merchandise::find($item['id_merchandise']);
                if (!$merch) throw new \Exception("Merchandise ID {$item['id_merchandise']} tidak ditemukan");

                if ($merch->jumlah < $item['jumlah']) {
                    throw new \Exception("Stok merchandise ID {$merch->id} tidak cukup");
                }

                $totalPoin += $merch->nilai_point * $item['jumlah'];
            }

            if ($selectedPembeli->point < $totalPoin) {
                throw new \Exception("Poin tidak mencukupi");
            }

            foreach ($items as $item) {
                $merch = Merchandise::find($item['id_merchandise']);

                $merch->jumlah -= $item['jumlah'];
                $merch->save();

                PenukaranMerchandise::create([
                    'id_pembeli' => $selectedPembeli->id,
                    'id_merchandise' => $merch->id,
                    'id_pegawai' => $pegawai ? $pegawai->id : null,
                    'tanggal_penukaran' => now(),
                    'jumlah' => $item['jumlah'],
                    'status' => 'selesai'
                ]);
            }

            $selectedPembeli->point -= $totalPoin;
            $selectedPembeli->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Semua item berhasil ditukarkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menukarkan', 'error' => $e->getMessage()], 500);
        }
    }


    // ===============================
    // Detail penukaran (boleh tanpa login)
    // ===============================
    public function show($id)
    {
        $penukaran = PenukaranMerchandise::with(['pembeli', 'pegawai', 'merchandise'])->find($id);
        if (!$penukaran) {
            return response()->json([
                'success' => false,
                'message' => 'Penukaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail penukaran',
            'data' => $penukaran
        ]);
    }

    // ===============================
    // Hapus penukaran (boleh tanpa login)
    // ===============================
    public function destroy($id)
    {
        $penukaran = PenukaranMerchandise::find($id);
        if (!$penukaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $penukaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data penukaran berhasil dihapus'
        ]);
    }

    // ===============================
    // Detail merchandise (boleh tanpa login)
    // ===============================
    public function showMerchandise($id)
    {
        $merchandise = Merchandise::find($id);

        if (!$merchandise) {
            return response()->json([
                'success' => false,
                'message' => 'Merchandise tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail merchandise',
            'data' => $merchandise
        ]);
    }


    // ===============================
    // Daftar semua merchandise
    // ===============================
    public function listMerchandise()
    {
        $merchandises = Merchandise::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua merchandise',
            'data' => $merchandises
        ]);
    }
}
