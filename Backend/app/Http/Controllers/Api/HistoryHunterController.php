<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use App\Models\Pembeli;
use App\Models\Pegawai; // Pastikan model Pegawai ada
use App\Models\Barang; // Pastikan model Barang ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Untuk perhitungan tanggal

class TransaksiPenjualanController extends Controller
{
    // ... (metode yang sudah ada) ...

    /**
     * Menampilkan history komisi dan detail komisi untuk Hunter yang login (Fungsi 420-422).
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexHunterHistory(Request $request)
    {
        $user = Auth::user(); // Dapatkan user yang sedang login

        // Pastikan user login dan adalah Pegawai dengan jabatan Hunter (id_jabatan 5)
        // Asumsi model User bisa jadi Pegawai langsung atau punya relasi ke Pegawai
        if (!$user || !($user instanceof \App\Models\Pegawai)) { // Sesuaikan jika user adalah generic User model
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya Hunter yang dapat melihat history ini.'], 403);
        }

        // Asumsi model Pegawai memiliki kolom 'jabatan' atau relasi ke 'jabatan'
        // Jika jabatan adalah ID (misal ID 5 untuk Hunter)
        if ($user->jabatan !== 5) { // <<<--- SESUAIKAN JIKA 'jabatan' adalah ID bukan nama
             return response()->json(['success' => false, 'message' => 'Akses ditolak: Anda bukan Hunter.'], 403);
        }

        $hunterId = $user->id; // ID pegawai yang login sebagai Hunter

        // Dapatkan transaksi penjualan yang barangnya terkait dengan hunter ini
        // dan status barangnya 'terjual'
        $transactions = TransaksiPenjualan::with([
            'pembeli',
            'detail.barang.penitip', // Memuat relasi penitip untuk perhitungan komisi
            'detail.barang.pegawai', // Jika pegawai di barang adalah hunter
            'detail.barang.hunter', // Relasi ke hunter di Barang
        ])
        ->whereHas('detail.barang', function($query) use ($hunterId) {
            $query->where('id_hunter', $hunterId)
                  ->where('status_barang', 'terjual'); // Hanya barang yang terjual
        })
        ->get();

        $processedTransactions = [];

        foreach ($transactions as $transaction) {
            $commissionDetails = [];
            $totalHunterCommission = 0;
            $totalReuseMartCommission = 0;
            $totalPenitipBonus = 0;

            foreach ($transaction->detail as $detail) {
                $barang = $detail->barang;

                // Pastikan barang ini memang terkait dengan hunter yang login
                if ($barang && $barang->id_hunter == $hunterId && $barang->status_barang == 'terjual') {
                    $hargaBarang = $barang->harga_barang;

                    // Komisi Hunter (5%)
                    $hunterCommission = $hargaBarang * 0.05;

                    // Komisi ReUse Mart: default 20%
                    $reuseMartCommissionRate = 0.20;
                    if ($barang->penambahan_durasi > 0) { // Jika masa titip diperpanjang
                        $reuseMartCommissionRate = 0.25; // Komisi 25%
                    } else {
                        $reuseMartCommissionRate = 0.20; // Komisi default 20%
                    }

                    $reuseMartCommission = $hargaBarang * $reuseMartCommissionRate;

                    // Bonus Penitip (10% dari komisi ReUse Mart jika laku < 7 hari)
                    $penitipBonus = 0;
                    $tglPenitipan = Carbon::parse($barang->tgl_penitipan);
                    $tglPengambilan = Carbon::parse($barang->tgl_pengambilan); // Asumsi tgl_pengambilan adalah tanggal laku/diterima

                    if ($tglPengambilan->diffInDays($tglPenitipan) < 7) {
                        $penitipBonus = $reuseMartCommission * 0.10; // 10% dari komisi Reuse Mart
                    }

                    $commissionDetails[] = [
                        'id_barang' => $barang->id,
                        'nama_barang' => $barang->nama_barang,
                        'harga_barang' => $hargaBarang,
                        'komisi_hunter' => $hunterCommission,
                        'komisi_reusemart' => $reuseMartCommission,
                        'bonus_penitip' => $penitipBonus,
                        'tgl_penitipan' => $barang->tgl_penitipan,
                        'tgl_pengambilan' => $barang->tgl_pengambilan,
                        'lama_titip_hari' => $tglPengambilan->diffInDays($tglPenitipan),
                    ];

                    $totalHunterCommission += $hunterCommission;
                    $totalReuseMartCommission += $reuseMartCommission;
                    $totalPenitipBonus += $penitipBonus;
                }
            }

            // Hanya tambahkan transaksi jika ada barang yang relevan untuk hunter ini
            if (!empty($commissionDetails)) {
                $processedTransactions[] = [
                    'id_transaksi' => $transaction->id,
                    'tgl_transaksi_penjualan' => $transaction->created_at,
                    'nama_pembeli' => $transaction->pembeli->nama_pembeli,
                    'total_komisi_hunter_transaksi_ini' => $totalHunterCommission,
                    'total_komisi_reusemart_transaksi_ini' => $totalReuseMartCommission,
                    'total_bonus_penitip_transaksi_ini' => $totalPenitipBonus,
                    'detail_komisi_barang' => $commissionDetails,
                ];
            }
        }

        if (empty($processedTransactions)) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada history komisi hunter ditemukan.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'History komisi hunter berhasil diambil.',
            'data' => $processedTransactions
        ], 200);
    }
}
