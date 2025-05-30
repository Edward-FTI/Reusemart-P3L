<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Pembeli;
use App\Models\Barang;
use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class TransaksiPenjualanController extends Controller
{
    private function getPembeliId()
    {
        $userEmail = Auth::user()->email;
        $pembeli = Pembeli::where('email', $userEmail)->first();
        return $pembeli ? $pembeli->id : null;
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'selected_cart_ids' => 'required|array',
        'selected_cart_ids.*' => 'exists:carts,id',
        'metode_pengiriman' => 'required|in:ambil sendiri,kurir',
        'alamat_pengiriman' => 'nullable|string',
        'bukti_pembayaran' => 'required|image|max:2048',
        'poin_ditukar' => 'nullable|integer|min:0',
    ]);

    $pembeliId = $this->getPembeliId();
    if (!$pembeliId) {
        return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
    }

    $pembeli = Pembeli::find($pembeliId);
    $carts = Cart::whereIn('id', $validated['selected_cart_ids'])
                ->where('id_pembeli', $pembeliId)
                ->with('barang')
                ->get();

    if ($carts->isEmpty()) {
        return response()->json(['message' => 'Cart tidak valid atau kosong'], 422);
    }

    // Validasi pengiriman kurir hanya untuk Yogyakarta
    if ($validated['metode_pengiriman'] === 'kurir') {
        if (!$validated['alamat_pengiriman']) {
            return response()->json(['message' => 'Alamat wajib diisi jika memilih kurir'], 422);
        }
        if (stripos($validated['alamat_pengiriman'], 'yogyakarta') === false) {
            return response()->json(['message' => 'Pengiriman dengan kurir hanya untuk area Yogyakarta'], 422);
        }
    }

    // Hitung total harga
    $totalHarga = $carts->sum(fn($cart) => $cart->barang->harga);

    // Hitung ongkir
    $ongkir = ($validated['metode_pengiriman'] === 'kurir' && $totalHarga < 1500000) ? 100000 : 0;

    // Hitung total bayar (termasuk ongkir)
    $totalBayar = $totalHarga + $ongkir;

    // Validasi poin ditukar
    $poinDimiliki = $pembeli->poin;
    $poinDitukar = $validated['poin_ditukar'] ?? 0;
    if ($poinDitukar > $poinDimiliki) {
        return response()->json(['message' => 'Poin yang ditukar melebihi jumlah yang dimiliki'], 422);
    }

    // Potong harga dengan penukaran poin (1 poin = 10.000)
    $potonganPoin = $poinDitukar * 10000;
    $totalBayar -= $potonganPoin;
    $totalBayar = max(0, $totalBayar); // Hindari nilai negatif

    // Simpan bukti pembayaran
    $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

    // Generate nomor transaksi: tahun.bulan.nomor_urut
    $prefix = now()->format('Y.m');
    $lastTrans = TransaksiPenjualan::where('no_transaksi', 'like', "$prefix.%")->count() + 1;
    $noTransaksi = "$prefix.$lastTrans";

    // Hitung bonus poin jika total harga > 500rb
    $bonusPoin = ($totalHarga > 500000) ? floor(($totalHarga / 10000) * 0.2) : 0;

    DB::beginTransaction();
    try {
        // Simpan transaksi
        $transaksi = TransaksiPenjualan::create([
            'no_transaksi' => $noTransaksi,
            'id_pembeli' => $pembeliId,
            'total_harga_pembelian' => $totalHarga,
            'ongkir' => $ongkir,
            'total_bayar' => $totalBayar,
            'alamat_pengiriman' => $validated['alamat_pengiriman'],
            'metode_pengiriman' => $validated['metode_pengiriman'],
            'bukti_pembayaran' => $buktiPath,
            'status_pengiriman' => 'menunggu',
            'status_pembelian' => 'menunggu',
            'verifikasi_pembayaran' => 'belum diverifikasi',
        ]);

        // Detail transaksi & tandai barang sold out
        foreach ($carts as $cart) {
            DetailTransaksiPenjualan::create([
                'transaksi_penjualan_id' => $transaksi->id,
                'id_barang' => $cart->id_barang,
                'harga_saat_transaksi' => $cart->barang->harga
            ]);

            $cart->barang->update(['status_barang' => 'sold out']);
        }

        // Hapus cart
        Cart::whereIn('id', $validated['selected_cart_ids'])->delete();

        // Update poin pembeli
        $pembeli->poin = $poinDimiliki - $poinDitukar + $bonusPoin;
        $pembeli->save();

        DB::commit();

        return response()->json([
            'message' => 'Transaksi berhasil',
            'transaksi' => $transaksi->load('detail.barang'),
            'sisa_poin' => $pembeli->poin
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Gagal menyimpan transaksi', 'error' => $e->getMessage()], 500);
    }
}

}
