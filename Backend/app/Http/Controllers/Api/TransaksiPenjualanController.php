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
use Illuminate\Support\Facades\Validator;

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
            // Ambil semua input
            $storeData = $request->all();

            // Validasi input
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

            // Ambil data yang sudah tervalidasi
            $validated = $validator->validated();

            // Handle upload gambar jika ada
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
            // Ambil ID pembeli
            $pembeliId = $this->getPembeliId();
            if ($pembeliId instanceof \Illuminate\Http\JsonResponse) {
                return $pembeliId;
            }

            $pembeli = Pembeli::find($pembeliId);
            if (!$pembeli) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan'], 404);
            }

            // Ambil cart pembeli
            $carts = Cart::where('id_pembeli', $pembeliId)->with('barang')->get();
            if ($carts->isEmpty()) {
                return response()->json(['message' => 'Cart kosong'], 400);
            }

            // Hitung total harga barang
            $totalHarga = 0;
            foreach ($carts as $cart) {
                if (!$cart->barang || $cart->barang->harga_barang === null) {
                    return response()->json(['message' => "Barang atau harga tidak tersedia di cart ID {$cart->id}"], 400);
                }
                $totalHarga += $cart->barang->harga_barang;
            }

            // Hitung ongkir
            $ongkir = 0;
            if ($validated['metode_pengiriman'] === 'diantar') {
                $ongkir = $totalHarga >= 1500000 ? 0 : 100000;
                if (empty($validated['alamat_pengiriman'])) {
                    return response()->json(['message' => 'Alamat pengiriman wajib diisi'], 400);
                }
            }

            // Hitung poin
            $poin_awal = floor($totalHarga / 10000);
            $bonus = $totalHarga > 500000 ? floor($poin_awal * 0.2) : 0;
            $poin_total = $poin_awal + $bonus;

            // Hitung poin yang dipakai
            $poin_digunakan = min($validated['poin_digunakan'] ?? 0, $pembeli->point);
            $totalSetelahPoin = max(0, ($totalHarga + $ongkir) - $poin_digunakan);

            // Simpan transaksi
            $transaksi = TransaksiPenjualan::create([
                'id_pembeli' => $pembeliId,
                'total_harga_pembelian' => $totalSetelahPoin,
                'metode_pengiriman' => $validated['metode_pengiriman'],
                'alamat_pengiriman' => $validated['metode_pengiriman'] === 'diantar' ? $validated['alamat_pengiriman'] : null,
                'ongkir' => $ongkir,
                'bukti_pembayaran' => $validated['bukti_pembayaran'] ?? '',
                'status_pengiriman' => $validated['status_pengiriman'],
                'status_pembelian' => $validated['status_pembelian'],
                'verifikasi_pembayaran' => false,
                'id_pegawai' => 1,
            ]);

            // Simpan detail transaksi
            foreach ($carts as $cart) {
                Detail_transaksi_penjualan::create([
                    'id_transaksi_penjualan' => $transaksi->id,
                    'id_barang' => $cart->barang->id,
                    'harga_saat_transaksi' => $cart->barang->harga_barang,
                ]);
            }

            // Update poin pembeli
            $pembeli->point = $pembeli->point - $poin_digunakan + $poin_total;
            $pembeli->save();

            // Hapus cart
            Cart::where('id_pembeli', $pembeliId)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil',
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
