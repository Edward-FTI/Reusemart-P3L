<?php

namespace App\Http\Controllers\Api;

use App\Models\TransaksiPengiriman;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use App\Models\Pegawai;
use DateTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\Detail_transaksi_penjualan;
use App\Models\Penitip;
use App\Models\Pembeli;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransaksiPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function getPegawaiId()
    {
        $userEmail = Auth::user()->email;
        $pegawai = Pegawai::where('email', $userEmail)->first();

        if (!$pegawai) {
            return null;
        }
        return $pegawai->id;
    }

    private function getPembeliId()
    {
        $userEmail = Auth::user()->email;
        $pembeli = Pembeli::where('email', $userEmail)->first();

        if (!$pembeli) {
            return null;
        }
        return $pembeli->id;
    }

    public function indexPublic()
    {
        $pengiriman = TransaksiPengiriman::with(['barang', 'pegawai'])->get();
        if (count($pengiriman) > 0) {
            return response([
                'message' => 'Berhasil mengambil data pengiriman',
                'data' => $pengiriman
            ], 200);
        }
        return response([
            'message' => 'Data Pengiriman kosong',
            'data' => []
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

        $pengiriman = TransaksiPengiriman::with(['barang', 'pegawai'])
            ->where('id_pegawai', $pegawaiId)
            ->get();

        if ($pengiriman->count() > 0) {
            return response([
                'message' => 'Berhasil mengambil data transaksi pengiriman',
                'data' => $pengiriman
            ], 200);
        }
        return response([
            'message' => 'Data Pengiriman kosong',
            'data' => []
        ], 200);
    }

    // Menampilkan hanya pengiriman dengan status 'Selesai'
    public function selesai()
    {
        $pegawaiId = $this->getPegawaiId();

        if (!$pegawaiId) {
            return response([
                'message' => 'Pegawai tidak ditemukan untuk user yang login'
            ], 404);
        }

        $pengiriman = TransaksiPengiriman::with([
            'barang',
            'pegawai',
            'transaksiPenjualan.pembeli'
        ])
            ->where('id_pegawai', $pegawaiId)
            ->where('status_pengiriman', 'Selesai')
            ->get();

        if ($pengiriman->count() > 0) {
            // Ambil data yang diformat sesuai kebutuhan
            $data = $pengiriman->map(function ($item) {
                return [
                    'id_transaksi_pengiriman' => $item->id,
                    'nama_pembeli' => optional($item->transaksiPenjualan->pembeli)->nama_pembeli,
                    'alamat_pembeli' => $item->transaksiPenjualan->alamat_pengiriman,
                    'biaya_pengiriman' => $item->biaya_pengiriman,
                    'catatan' => $item->catatan,
                ];
            });

            return response([
                'message' => 'Berhasil mengambil data pengiriman yang sudah selesai',
                'data' => $data
            ], 200);
        }

        return response([
            'message' => 'Tidak ada pengiriman yang selesai',
            'data' => []
        ], 200);
    }
    // use Carbon\Carbon; // Removed, now imported at the top

    public function selesaiBarang()
    {
        $pegawaiId = $this->getPegawaiId();

        if (!$pegawaiId) {
            return response([
                'message' => 'Pegawai tidak ditemukan untuk user yang login'
            ], 404);
        }

        $tanggalSekarang = Carbon::now();
        $tanggalSebulanKeDepan = $tanggalSekarang->copy()->addMonth();


        $pengiriman = TransaksiPengiriman::with([
            'pegawai',
            'transaksiPenjualan.detailTransaksi.barang'
        ])
            ->where('id_pegawai', 12)
            // ->where('status_pengiriman', 'Selesai')
            ->whereBetween('tgl_pengiriman', [$tanggalSekarang, $tanggalSebulanKeDepan])
            ->get();

        if ($pengiriman->count() > 0) {
            $data = $pengiriman->map(function ($item) {
                $barangList = [];

                foreach ($item->transaksiPenjualan->detailTransaksi as $detail) {
                    $barang = $detail->barang;
                    if ($barang) {
                        $barangList[] = [
                            'nama_barang' => $barang->nama_barang,
                            'status_barang' => $barang->status_barang,
                        ];
                    }
                }

                return [
                    'id_transaksi_pengiriman' => $item->id,
                    'tanggal_jadwal_kirim' => $item->tgl_pengiriman,
                    'nama_kurir' => optional($item->pegawai)->nama,
                    'biaya_pengiriman' => $item->biaya_pengiriman,
                    'barang' => $barangList,
                ];
            });

            return response([
                'message' => 'Berhasil mengambil data pengiriman yang sudah selesai dalam 1 bulan terakhir',
                'data' => $data
            ], 200);
        }

        return response([
            'message' => 'Tidak ada pengiriman yang selesai dalam 1 bulan terakhir',
            'data' => []
        ], 200);
    }




    // Menampilkan hanya pengiriman dengan status 'Proses'
    public function proses()
    {
        $pegawaiId = $this->getPegawaiId();

        if (!$pegawaiId) {
            return response([
                'message' => 'Pegawai tidak ditemukan untuk user yang login'
            ], 404);
        }

        $pengiriman = TransaksiPengiriman::with([
            'barang',
            'pegawai',
            'transaksiPenjualan.pembeli'
        ])
            ->where('id_pegawai', $pegawaiId)
            ->where('status_pengiriman', 'Proses')
            ->get();

        if ($pengiriman->count() > 0) {
            $data = $pengiriman->map(function ($item) {
                return [
                    'id_transaksi_pengiriman' => $item->id,
                    'nama_pembeli'     => optional($item->transaksiPenjualan->pembeli)->nama_pembeli,
                    'alamat_pembeli'   => $item->transaksiPenjualan->alamat_pengiriman,
                    'biaya_pengiriman' => $item->biaya_pengiriman,
                    'tgl_pengiriman'          => $item->tgl_pengiriman,
                ];
            });

            return response([
                'message' => 'Berhasil mengambil data pengiriman yang sedang diproses',
                'data' => $data
            ], 200);
        }

        return response([
            'message' => 'Tidak ada pengiriman yang sedang diproses',
            'data' => []
        ], 200);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengiriman = TransaksiPengiriman::with(['barang', 'pegawai'])->find($id);

        if (!$pengiriman) {
            return response([
                'message' => 'Data Pengiriman tidak ditemukan'
            ], 404);
        }

        return response([
            'message' => 'Berhasil mengambil data pengiriman',
            'data' => $pengiriman
        ], 200);
    }

    public function showAllBarangs(string $id)
    {
        $barang = Barang::find($id);
        if (!is_null($barang)) {
            return response([
                'message' => 'Berhasil mengambil data barang',
                'data' => $barang
            ], 200);
        }
        return response([
            'message' => 'Barang tidak ditemukan',
            'data' => null
        ], 404);
    }


    public function updateStatusSelesai(Request $request, $id)
    {
        $pengiriman = TransaksiPengiriman::find($id);

        if (!$pengiriman) {
            return response([
                'message' => 'Data pengiriman tidak ditemukan'
            ], 404);
        }

        $penjualan = TransaksiPenjualan::find($pengiriman->id_transaksi_penjualan);

        if (!$penjualan) {
            return response([
                'message' => 'Data transaksi penjualan tidak ditemukan'
            ], 404);
        }

        // Update status pengiriman
        $pengiriman->status_pengiriman = 'Selesai';
        $pengiriman->catatan = 'Tiba Di Tujuan';
        $pengiriman->save();

        // Update status penjualan
        $penjualan->status_pengiriman = 'Selesai';
        $penjualan->status_pembelian = 'Selesai';
        $penjualan->save();

        // Kirim notifikasi
        $notificationService = app(NotificationService::class);

        // Ambil semua detail transaksi terkait transaksi penjualan ini
        $detailTransaksi = Detail_transaksi_penjualan::where('id_transaksi_penjualan', $penjualan->id)->get();

        // Optimalisasi agar 1 penitip 1 notifikasi saja
        $penitipNotifSent = [];

        foreach ($detailTransaksi as $detail) {
            $barang = Barang::find($detail->id_barang);
            if ($barang) {
                $penitip = Penitip::find($barang->id_penitip);
                if ($penitip && !in_array($penitip->id, $penitipNotifSent)) {
                    $userPenitip = User::where('email', $penitip->email)->first();
                    if ($userPenitip) {
                        Log::info("Notif Penitip: {$userPenitip->email}");
                        $notificationService->sendNotification(
                            $userPenitip->id,
                            'Pesanan Sudah Sampai',
                            "Beberapa barang Anda telah diterima oleh pembeli. Terima kasih atas partisipasinya."
                        );
                        $penitipNotifSent[] = $penitip->id;
                    }
                }
            }
        }

        // Notifikasi ke pembeli
        $pembeli = Pembeli::find($penjualan->id_pembeli);
        if ($pembeli) {
            $userPembeli = User::where('email', $pembeli->email)->first();
            if ($userPembeli) {
                Log::info("Notif Pembeli: {$userPembeli->email}");
                $notificationService->sendNotification(
                    $userPembeli->id,
                    'Pesanan Telah Diterima',
                    "Pesanan Anda sudah tiba. Terima kasih telah berbelanja!"
                );
            }
        }

        return response([
            'message' => 'Status pengiriman dan penjualan berhasil diperbarui ke Selesai & notifikasi telah dikirim',
            'data' => [
                'pengiriman' => $pengiriman,
                'penjualan' => $penjualan
            ]
        ], 200);
    }


    public function transaksiDisiapkanPembeli()
    {
        $userEmail = Auth::user()->email;
        $pembeli = Pembeli::where('email', $userEmail)->first();

        if (!$pembeli) {
            return response([
                'message' => 'Pembeli tidak ditemukan untuk user yang login',
                'data' => []
            ], 404);
        }

        $pengiriman = TransaksiPengiriman::with('transaksiPenjualan')
            ->whereHas('transaksiPenjualan', function ($query) use ($pembeli) {
                $query->where('id_pembeli', $pembeli->id);
            })
            ->where('status_pengiriman', 'disiapkan')
            ->whereNull('tgl_pengiriman')
            ->get();

        if ($pengiriman->count() > 0) {
            $data = $pengiriman->map(function ($item) {
                return [
                    'no_transaksi' => $item->id_transaksi_penjualan,
                    'tanggal_transaksi' => optional($item->transaksiPenjualan)->tgl_transaksi,
                    'total_transaksi' => optional($item->transaksiPenjualan)->total_harga_pembelian,
                    'status_pengiriman' => $item->status_pengiriman,
                ];
            });

            return response([
                'message' => 'Berhasil mengambil data transaksi yang sedang disiapkan',
                'data' => $data
            ], 200);
        }

        return response([
            'message' => 'Tidak ada transaksi yang sedang disiapkan',
            'data' => []
        ], 200);
    }

    public function batalkanTransaksiDisiapkan($id)
    {
        $userEmail = Auth::user()->email;
        $pembeli = Pembeli::where('email', $userEmail)->first();

        if (!$pembeli) {
            return response([
                'message' => 'Pembeli tidak ditemukan untuk user yang login',
            ], 404);
        }

        // Ambil pengiriman terkait
        $pengiriman = TransaksiPengiriman::where('id_transaksi_penjualan', $id)
            ->where('status_pengiriman', 'disiapkan')
            ->whereNull('tgl_pengiriman')
            ->first();

        if (!$pengiriman) {
            return response([
                'message' => 'Transaksi tidak ditemukan atau tidak dalam status disiapkan',
            ], 404);
        }

        $penjualan = $pengiriman->transaksiPenjualan;

        // Validasi kepemilikan transaksi oleh pembeli
        if ($penjualan->id_pembeli != $pembeli->id) {
            return response([
                'message' => 'Anda tidak memiliki akses ke transaksi ini',
            ], 403);
        }

        // Ubah status transaksi penjualan dan pengiriman
        $penjualan->status_pembelian = 'dibatalkan pembeli';
        $penjualan->status_pengiriman = 'dibatalkan pembeli';
        $penjualan->save();

        $pengiriman->status_pengiriman = 'dibatalkan pembeli';
        $pengiriman->save();

        // Ubah status barang kembali ke "Dijual"
        foreach ($penjualan->detailTransaksi as $detail) {
            $barang = $detail->barang;
            if ($barang) {
                $barang->status_barang = 'Dijual';
                $barang->save();
            }
        }

        // Tambahkan poin ke pembeli (1 poin per 10.000)
        $total = $penjualan->total_harga_pembelian;
        $poinBaru = floor($total / 10000);

        $pembeli->poin += $poinBaru;
        $pembeli->save();

        return response([
            'message' => 'Transaksi berhasil dibatalkan dan poin dikembalikan',
            'data' => [
                'total_transaksi' => $total,
                'poin_ditambahkan' => $poinBaru,
                'total_poin_sekarang' => $pembeli->poin
            ]
        ], 200);
    }
}
