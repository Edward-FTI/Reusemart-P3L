<?php

namespace App\Http\Controllers\Api;

use App\Services\NotificationService; // Pastikan ini sesuai dengan namespace NotificationService
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DateTime;
use App\Models\Barang;
use App\Models\Penitip; // Menggunakan model Penitip
use App\Models\User; // Menggunakan model User
use App\Models\TransaksiPengiriman; // Menggunakan model TransaksiPengiriman
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\TransaksiPenjualan; // Menggunakan model TransaksiPenjualan
use App\Models\Detail_transaksi_penjualan; // Menggunakan model Detail_transaksi_penjualan

class PengambilanController extends Controller
{

    private function getPegawai()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'Pegawai Gudang') {
            return null;
        }

        return Pegawai::where('email', $user->email)->first();
    }

    public function index()
    {
        $pengambilan = TransaksiPengiriman::with(['transaksiPenjualan.pembeli', 'transaksiPenjualan.detail.barang'])
            ->where('status_pengiriman', 'proses') // Filter berdasarkan status pengiriman
            ->get();

        // Memeriksa apakah ada data transaksi pengiriman
        if ($pengambilan->isNotEmpty()) {
            return response()->json([
                'message' => 'Berhasil mengambil data pengambilan',
                'data' => $pengambilan // Data akan mencakup relasi yang dimuat
            ], 200);
        }


        return response()->json([
            'message' => 'Data pengambilan kosong',
            'data' => []
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi input request
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required|exists:transaksi_pengirimans,id', // Validasi ID transaksi pengiriman
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek pegawai login
        $pegawai = $this->getPegawai();
        if (!$pegawai) {
            return response()->json([
                'message' => 'Pegawai tidak ditemukan atau bukan Pegawai Gudang',
            ], 403);
        }

        // Mencari transaksi pengiriman berdasarkan ID
        $transaksi = TransaksiPengiriman::find($request->id_transaksi);
        if (!$transaksi) {
            return response()->json([
                'message' => 'Transaksi Pengiriman tidak ditemukan'
            ], 404);
        }

        $transaksi->status_pengiriman = 'diambil';
        $transaksi->id_pegawai = $pegawai->id;
        $transaksi->save();

        return response()->json([
            'message' => 'Pengambilan berhasil disimpan',
            'data' => $transaksi
        ], 200);
    }

    public function show($id)
    {
        $transaksi = TransaksiPengiriman::with(['transaksiPenjualan.pembeli', 'transaksiPenjualan.detail.barang'])->find($id);

        // Jika transaksi tidak ditemukan
        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => "Detail pengambilan ID: $id",
            'data' => $transaksi // Data akan mencakup relasi yang dimuat
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPengiriman::find($id);

        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        $transaksi->tgl_pengiriman = $request->input('tgl_pengiriman', $transaksi->tgl_pengiriman);
        $transaksi->status_pengiriman = $request->input('status_pengiriman', $transaksi->status_pengiriman);
        $transaksi->catatan = $request->input('catatan', $transaksi->catatan);
        $transaksi->id_pegawai = $request->input('id_pegawai', $transaksi->id_pegawai);
        $transaksi->save();

        // Ambil data terkait untuk notifikasi
        $notificationService = app(NotificationService::class);

        // 1. Kirim notifikasi ke semua penitip dari barang-barang dalam transaksi ini
        $barangs = $transaksi->barangs; // pastikan relasi `barangs` didefinisikan di model
        foreach ($barangs as $barang) {
            $penitip = Penitip::where('id', $barang->id_penitip)->first();
            if ($penitip) {
                $userPenitip = User::where('email', $penitip->email)->first();
                if ($userPenitip) {
                    Log::info("Notif Penitip: {$userPenitip->email} - Barang: {$barang->nama_barang}");
                    $notificationService->sendNotification(
                        $userPenitip->id,
                        'Barang Dikirim',
                        "Barang {$barang->nama_barang} sedang dikirim."
                    );
                }
            }
        }

        // 2. Kirim notifikasi ke pembeli
        $pembeli = $transaksi->pembeli; // pastikan relasi `pembeli` didefinisikan di model
        if ($pembeli) {
            $userPembeli = User::where('email', $pembeli->email)->first();
            if ($userPembeli) {
                Log::info("Notif Pembeli: {$userPembeli->email}");
                $notificationService->sendNotification(
                    $userPembeli->id,
                    'Barang Dalam Pengiriman',
                    "Pesanan Anda sedang dikirim. Mohon ditunggu."
                );
            }
        }

        // 3. Kirim notifikasi ke pegawai yang ditugaskan
        $pegawaiId = $transaksi->id_pegawai;
        $userPegawai = User::find($pegawaiId);
        if ($userPegawai) {
            Log::info("Notif Pegawai: {$userPegawai->email}");
            $notificationService->sendNotification(
                $userPegawai->id,
                'Tugas Pengiriman',
                "Anda telah ditugaskan untuk pengiriman ID {$transaksi->id}."
            );
        }

        return response()->json([
            'message' => "Pengambilan ID $id berhasil diperbarui",
            'data' => $transaksi
        ], 200);
    }

    public function destroy($id)
    {
        // Mencari transaksi pengiriman berdasarkan ID
        $transaksi = TransaksiPengiriman::find($id);

        // Jika transaksi tidak ditemukan
        if (!$transaksi) {
            return response()->json([
                'message' => 'Data pengambilan tidak ditemukan'
            ], 404);
        }

        // Menghapus transaksi
        $transaksi->delete();

        return response()->json([
            'message' => "Pengambilan ID $id berhasil dihapus"
        ], 200);
    }

    public function prosesTransaksiHangusOtomatis()
    {
        $pengirimanList = TransaksiPengiriman::with('transaksiPenjualan.detail.barang')
            ->whereNotNull('tgl_pengambilan')
            ->where('status_transaksi', '!=', 'Hangus')
            ->get();

        $count = 0;

        foreach ($pengirimanList as $pengiriman) {
            $tglPengambilan = new DateTime($pengiriman->tgl_pengambilan);
            $hariIni = new DateTime();

            $selisih = $hariIni->diff($tglPengambilan)->days;

            if ($hariIni > $tglPengambilan && $selisih >= 2) {
                $pengiriman->status_transaksi = 'Hangus';
                $pengiriman->save();

                $transaksiPenjualan = $pengiriman->transaksiPenjualan;
                if ($transaksiPenjualan) {
                    foreach ($transaksiPenjualan->detail as $detail) {
                        $barang = $detail->barang;
                        if ($barang) {
                            $barang->status_barang = 'barang untuk donasi';
                            $barang->save();
                        }
                    }
                }

                $count++;
            }
        }

        return response()->json([
            'message' => "Pengecekan selesai. $count transaksi diperbarui menjadi 'Hangus'.",
        ]);
    }
}
