<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Pembeli;
use App\Models\TransaksiPenjualan;
use App\Models\Detail_transaksi_penjualan;
use App\Models\Penitip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;

class TransaksiPenjualanController extends Controller
{
    private function getPembeliId()
    {
        try {
            $user = Auth::user();
            if (!$user || !isset($user->email)) {
                return response()->json(['message' => 'User belum login atau tidak memiliki email'], 401);
            }

            $pembeli = Pembeli::where('email', $user->email)->first();
            if (!$pembeli) {
                return response()->json(['message' => 'Pembeli tidak ditemukan untuk email tersebut'], 404);
            }

            return $pembeli->id;
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal mengambil ID pembeli', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $storeData = $request->all();

            $validator = Validator::make($storeData, [
                'metode_pengiriman' => 'required|string|in:diantar,diambil',
                'alamat_pengiriman' => 'nullable|string',
                'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status_pengiriman' => 'required|string|in:diantar,tiba di tujuan',
                'status_pembelian' => 'required|string',
                'poin_digunakan' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            if ($request->hasFile('bukti_pembayaran')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->file('bukti_pembayaran')->extension();
                $pathGambar = 'images/pembayaran/' . $imageName;
                $request->file('bukti_pembayaran')->move(public_path('images/pembayaran'), $imageName);
                $validated['bukti_pembayaran'] = $pathGambar;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $pembeliId = $this->getPembeliId();
            if ($pembeliId instanceof \Illuminate\Http\JsonResponse) {
                return $pembeliId;
            }

            $pembeli = Pembeli::find($pembeliId);
            if (!$pembeli) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan'], 404);
            }

            $carts = Cart::where('id_pembeli', $pembeliId)->with('barang')->get();
            if ($carts->isEmpty()) {
                return response()->json(['message' => 'Cart kosong'], 400);
            }

            $totalHarga = 0;
            foreach ($carts as $cart) {
                if (!$cart->barang || $cart->barang->harga_barang === null) {
                    return response()->json(['message' => "Barang atau harga tidak tersedia di cart ID {$cart->id}"], 400);
                }
                $totalHarga += $cart->barang->harga_barang;
            }

            $ongkir = 0;
            if ($validated['metode_pengiriman'] === 'diantar') {
                $ongkir = $totalHarga >= 1500000 ? 0 : 100000;
                if (empty($validated['alamat_pengiriman'])) {
                    return response()->json(['message' => 'Alamat pengiriman wajib diisi'], 400);
                }
            }

            // Perhitungan poin
            $poin_awal = floor(max(0, $totalHarga - 20000) / 10000);
            $bonus = $totalHarga > 500000 ? floor($poin_awal * 0.2) : 0;
            $poin_total = $poin_awal + $bonus;

            // Gunakan poin (1 poin = 100 rupiah)
            $poin_digunakan = min($validated['poin_digunakan'] ?? 0, $pembeli->point);
            $potongan_dari_poin = $poin_digunakan * 100;

            $totalSetelahPoin = max(0, ($totalHarga + $ongkir) - $potongan_dari_poin);

            if ($pembeli->saldo < $totalSetelahPoin) {
                return response()->json([
                    'message' => 'Saldo tidak cukup untuk melakukan transaksi',
                    'saldo_tersedia' => $pembeli->saldo,
                    'total_yang_dibutuhkan' => $totalSetelahPoin
                ], 400);
            }

            $transaksi = TransaksiPenjualan::create([
                'id_pembeli' => $pembeliId,
                'total_harga_pembelian' => $totalSetelahPoin,
                'metode_pengiriman' => $validated['metode_pengiriman'],
                'alamat_pengiriman' => $validated['metode_pengiriman'] === 'diantar' ? $validated['alamat_pengiriman'] : null,
                'ongkir' => $ongkir,
                'bukti_pembayaran' => $validated['bukti_pembayaran'] ?? '',
                'status_pengiriman' => $validated['metode_pengiriman'] === 'diantar' ? 'diantar' : 'Menunggu Diambil',
                'status_pembelian' => $validated['status_pembelian'],
                'verifikasi_pembayaran' => false,
            ]);

            DB::table('transaksi_pengiriman')->insert([
                'id_transaksi_penjualan' => $transaksi->id,
                'id_pegawai' => 0,
                'tgl_pengiriman' => now(),
                'status_pengiriman' => 'diproses',
                'biaya_pengiriman' => $ongkir,
                'catatan' => null
            ]);

            foreach ($carts as $cart) {
                Detail_transaksi_penjualan::create([
                    'id_transaksi_penjualan' => $transaksi->id,
                    'id_barang' => $cart->barang->id,
                    'harga_saat_transaksi' => $cart->barang->harga_barang,
                ]);

                $cart->barang->status_barang = 'Sold Out';
                $cart->barang->save();


                $penitip = $cart->barang->penitip; // Pastikan relasi 'penitip' tersedia di model Barang
                if ($penitip) {
                    $komisi = $cart->barang->harga_barang * 0.8;
                    $penitip->saldo += $komisi;
                    $penitip->save();
                }
            }

            $pembeli->saldo -= $totalSetelahPoin;
            $pembeli->point = $pembeli->point - $poin_digunakan + $poin_total;
            $pembeli->save();

            Cart::where('id_pembeli', $pembeliId)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil',
                'data' => [
                    'transaksi' => $transaksi,
                    'poin_didapat' => $poin_total,
                    'poin_terpakai' => $poin_digunakan,
                    'poin_sisa' => $pembeli->point,
                    'saldo_sisa' => $pembeli->saldo
                ]
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaksi Gagal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menyimpan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function indexPembeli()
    {
        try {
            $pembeliId = $this->getPembeliId();
            if ($pembeliId instanceof \Illuminate\Http\JsonResponse) {
                return $pembeliId;
            }

            $transaksi = TransaksiPenjualan::with(['detailTransaksi.barang'])
                ->where('id_pembeli', $pembeliId)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                return response()->json([
                    'message' => 'Belum ada transaksi untuk pembeli ini',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Daftar transaksi pembeli',
                'data' => $transaksi
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function indexAdmin()
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
                'message' => 'Daftar seluruh transaksi',
                'data' => $transaksi
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifikasiPembayaran($id)
    {
        try {
            $transaksi = TransaksiPenjualan::select('id', 'verifikasi_pembayaran')
                // with detail transaksi
                ->with(['detailTransaksi.barang'])
                ->find($id);

            if (!$transaksi) {
                return response()->json([
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            $transaksi->verifikasi_pembayaran = true;
            $transaksi->save();
            // all barang yang ada di detail transaksi
            $detailTransaksi = $transaksi->detailTransaksi;
            if ($detailTransaksi->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada barang dalam transaksi ini'
                ], 404);
            }
            $barangs = $detailTransaksi->pluck('barang');
            if ($barangs->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada barang dalam transaksi ini'
                ], 404);
            }

            foreach ($barangs as $barang) {
                $penitip = Penitip::where('id', $barang->id_penitip)->first();
                $user = User::where('email', $penitip->email)->first();
                Log::info('Notifikasi untuk user: ' . $user->email . ' dengan FCM Token: ' . $user->fcm_token . ' untuk barang: ' . $barang->nama_barang);
                $notificationService = app(NotificationService::class);
                $notificationService->sendNotification($user->id, 'Barang Laku!!', 'Barang ' . $barang->nama_barang . ' telah diverifikasi.');
            }

            return response()->json([
                'message' => 'Pembayaran berhasil diverifikasi',
                'data' => $transaksi
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memverifikasi pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
