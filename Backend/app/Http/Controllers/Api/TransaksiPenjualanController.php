<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Pembeli;
use App\Models\TransaksiPenjualan;
use App\Models\Detail_transaksi_penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

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
            $validated = $request->validate([
                'metode_pengiriman' => 'required|string|in:diantar,diambil',
                'alamat_pengiriman' => 'nullable|string',
                'bukti_pembayaran' => 'nullable|string',
                'status_pengiriman' => 'required|string|in:di antar,tiba di tujuan',
                'status_pembelian' => 'required|string',
                'verifikasi_pembayaran' => 'required|boolean',
                'poin_digunakan' => 'nullable|integer|min:0',
            ]);
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
                return response()->json(['message' => 'Data pembeli tidak ditemukan di database'], 404);
            }

            $carts = Cart::where('id_pembeli', $pembeliId)->with('barang')->get();
            if ($carts->isEmpty()) {
                return response()->json(['message' => 'Cart kosong'], 400);
            }

            $totalHarga = 0;
            foreach ($carts as $cart) {
                if (!$cart->barang) {
                    return response()->json(['message' => "Barang pada cart ID {$cart->id} tidak ditemukan"], 404);
                }
                if ($cart->barang->harga_barang === null) {
                    return response()->json(['message' => "Harga barang dengan ID {$cart->id_barang} tidak tersedia"], 400);
                }
                $totalHarga += $cart->barang->harga_barang;
            }

            $ongkir = 0;
            if ($validated['metode_pengiriman'] === 'diantar') {
                $ongkir = $totalHarga >= 1500000 ? 0 : 100000;
                if (empty($validated['alamat_pengiriman'])) {
                    return response()->json(['message' => 'Alamat pengiriman wajib diisi untuk pengiriman diantar'], 400);
                }
            }

            $poin_awal = floor($totalHarga / 10000);
            $bonus = $totalHarga > 500000 ? floor($poin_awal * 0.2) : 0;
            $poin_total = $poin_awal + $bonus;

            $poin_digunakan = min($validated['poin_digunakan'] ?? 0, $pembeli->point);
            $totalSetelahPoin = max(0, ($totalHarga + $ongkir) - $poin_digunakan);

            $transaksi = TransaksiPenjualan::create([
                'id_pembeli' => $pembeliId,
                'total_harga_pembelian' => $totalSetelahPoin,
                'metode_pengiriman' => $validated['metode_pengiriman'],
                'alamat_pengiriman' => $validated['metode_pengiriman'] === 'diantar' ? $validated['alamat_pengiriman'] : null,
                'ongkir' => $ongkir,
                'bukti_pembayaran' => $validated['bukti_pembayaran'] ?? '',
                'status_pengiriman' => $validated['status_pengiriman'],
                'status_pembelian' => $validated['status_pembelian'],
                'verifikasi_pembayaran' => $validated['verifikasi_pembayaran'],
                'id_pegawai' => 1,
            ]);

            $totalHarga = 0;
            foreach ($carts as $cart) {
                if (!$cart->barang) {
                    return response()->json(['message' => "Barang pada cart ID {$cart->id} tidak ditemukan"], 404);
                }

                $barang = $cart->barang; // Assign objek barang

                if ($barang->harga_barang === null) {
                    return response()->json(['message' => "Harga barang dengan ID {$cart->id_barang} tidak tersedia"], 400);
                }

                $totalHarga += $barang->harga_barang;

                Detail_transaksi_penjualan::create([
                    'id_transaksi_penjualan' => $transaksi->id,
                    'id_barang' => $barang->id,
                    'harga_saat_transaksi' => $barang->harga_barang,
                ]);
            }



            $pembeli->point = $pembeli->point - $poin_digunakan + $poin_total;
            $pembeli->save();

            Cart::where('id_pembeli', $pembeliId)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'transaksi' => $transaksi,
                    'poin_didapat' => $poin_total,
                    'poin_terpakai' => $poin_digunakan,
                    'poin_sisa' => $pembeli->point,
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
}
