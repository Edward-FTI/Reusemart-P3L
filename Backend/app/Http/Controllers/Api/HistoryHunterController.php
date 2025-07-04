<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Pembeli;
use App\Models\Pegawai;
use App\Models\TransaksiPenjualan;
use App\Models\Detail_transaksi_penjualan;
use App\Models\Penitip;
use App\Models\User;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;
use Exception;
use Carbon\Carbon;

class TransaksiPenjualanController extends Controller
{
    public function barangHunterLogin(Request $request)
    {
        $user = Auth::user();

        // Cari pegawai berdasarkan email user login
        $pegawai = Pegawai::where('email', $user->email)->first();

        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        // Ambil barang di mana pegawai ini adalah hunter-nya
        $barang = Barang::with(['penitip', 'kategori_barang', 'pegawai', 'hunter'])
            ->where('id_hunter', $pegawai->id)
            ->get();

        return response()->json($barang);
    }

    public function publicIndex()
    {
        try {
            $transaksi = TransaksiPenjualan::with([
                'pembeli:id,nama_pembeli,email',
                'detailTransaksi.barang'
            ])
                ->orderBy('created_at', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                return response()->json([
                    'message' => 'Belum ada transaksi',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Daftar seluruh transaksi (public)',
                'data' => $transaksi
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function indexHunterHistory()
    {
        try {
            $user = Auth::user();
            if (!$user || strtolower($user->role) !== 'hunter') {
                return response()->json([
                    'status' => false,
                    'message' => 'Hanya hunter yang dapat mengakses data ini',
                ], 403);
            }

            $pegawai = Pegawai::where('email', $user->email)->first();
            if (!$pegawai) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data pegawai tidak ditemukan untuk hunter ini',
                ], 404);
            }

            $barangIds = Barang::where('id_hunter', $pegawai->id)
                ->where('status_barang', 'Sold Out')
                ->pluck('id');

            if ($barangIds->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Tidak ada barang hunter ini yang terjual',
                    'data' => [],
                ]);
            }

            $transaksiIds = Detail_transaksi_penjualan::whereIn('id_barang', $barangIds)
                ->pluck('id_transaksi_penjualan')
                ->unique();

            $transaksi = TransaksiPenjualan::with([
                'detailTransaksi.barang',
                'detailTransaksi.transaksi',
                'pembeli:id,nama_pembeli,email'
            ])
                ->whereIn('id', $transaksiIds)
                ->orderBy('created_at', 'desc')
                ->get();

            $transaksi = $transaksi->map(function ($item) use ($pegawai) {
                $item->detail_transaksi = $item->detailTransaksi->filter(function ($detail) use ($pegawai) {
                    return $detail->barang && $detail->barang->id_hunter == $pegawai->id;
                })->map(function ($detail) {
                    $barang = $detail->barang;
                    $tglMasuk = \Carbon\Carbon::parse($barang->tgl_penitipan);
                    $tglLaku = \Carbon\Carbon::parse($detail->transaksi->tgl_transaksi ?? $barang->updated_at);
                    $selisihHari = $tglMasuk->diffInDays($tglLaku);

                    $hargaJual = $barang->harga_barang;
                    $persenKomisi = $barang->penambahan_durasi > 0 ? 30 : 20;
                    $komisiReuseMart = ($persenKomisi / 100) * $hargaJual;
                    $komisiHunter = $barang->id_hunter ? (5 / 100) * $hargaJual : 0;

                    $bonusPenitip = $selisihHari < 7 ? (10 / 100) * ((20 / 100) * $hargaJual) : 0;
                    $komisiAkhirReuseMart = $komisiReuseMart - $bonusPenitip;

                    return [
                        'id' => $detail->id,
                        'harga_saat_transaksi' => $detail->harga_saat_transaksi,
                        'barang' => $barang,
                        'komisi_hunter' => round($komisiHunter),
                        'komisi_reusemart' => round($komisiAkhirReuseMart),
                        'bonus_penitip' => round($bonusPenitip),
                        'tgl_laku' => $tglLaku->toDateString(),
                        'tgl_masuk' => $tglMasuk->toDateString()
                    ];
                })->values();

                return $item;
            })->filter(function ($item) {
                return $item->detail_transaksi->isNotEmpty();
            })->values();



            return response()->json([
                'status' => true,
                'message' => 'Data transaksi hunter berhasil diambil',
                'data' => $transaksi,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getTransaksiByJabatan5()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User belum login',
                ], 401);
            }

            $pegawai = Pegawai::where('email', $user->email)
                ->where('id_jabatan', 5)
                ->first();

            if (!$pegawai) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pegawai dengan jabatan ID 5 tidak ditemukan atau bukan pegawai',
                ], 403);
            }

            $barangIds = Barang::where('id_pegawai', $pegawai->id)
                ->pluck('id');

            if ($barangIds->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Tidak ada barang yang dikelola oleh pegawai ini',
                    'data' => [],
                ]);
            }

            $transaksiIds = Detail_transaksi_penjualan::whereIn('id_barang', $barangIds)
                ->pluck('id_transaksi_penjualan')
                ->unique();

            $transaksi = TransaksiPenjualan::with([
                'detailTransaksi.barang',
                'pembeli:id,nama_pembeli,email'
            ])
                ->whereIn('id', $transaksiIds)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data transaksi untuk pegawai dengan jabatan ID 5 berhasil diambil',
                'data' => $transaksi,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
