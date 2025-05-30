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
            'metode_pengiriman' => 'required|in:ambil sendiri,di antar',
            'alamat_pengiriman' => 'required|string',
            'bukti_pembayaran' => 'required|image|max:2048',
            'status_pengiriman' => 'required|string',
            'id_pegawai' => 'nullable|exists:pegawais,id',
            'status_pembelian' => 'nullable|string',
            'verifikasi_pembayaran' => 'nullable|string'
        ]);

        $pembeliId = $this->getPembeliId();
        if (!$pembeliId) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        // Ambil cart yang dipilih
        $carts = Cart::whereIn('id', $validated['selected_cart_ids'])
                     ->where('id_pembeli', $pembeliId)
                     ->with('barang')
                     ->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart tidak valid atau kosong'], 422);
        }

        // Hitung total harga
        $totalHarga = 0;
        foreach ($carts as $cart) {
            $totalHarga += $cart->barang->harga;
        }

        // Hitung ongkir
        $ongkir = $totalHarga > 1500000 ? 0 : 100000;

        // Simpan foto
        $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Simpan transaksi penjualan
        $transaksi = TransaksiPenjualan::create([
            'id_pembeli' => $pembeliId,
            'total_harga_pembelian' => $totalHarga,
            'alamat_pengiriman' => $validated['alamat_pengiriman'],
            'metode_pengiriman' => $validated['metode_pengiriman'],
            'ongkir' => $ongkir,
            'bukti_pembayaran' => $buktiPath,
            'status_pengiriman' => $validated['status_pengiriman'],
            'id_pegawai' => $validated['id_pegawai'] ?? null,
            'status_pembelian' => $validated['status_pembelian'] ?? 'menunggu',
            'verifikasi_pembayaran' => $validated['verifikasi_pembayaran'] ?? 'belum diverifikasi',
        ]);

        // Simpan detail transaksi
        foreach ($carts as $cart) {
            DetailTransaksiPenjualan::create([
                'transaksi_penjualan_id' => $transaksi->id,
                'id_barang' => $cart->id_barang,
                'harga_saat_transaksi' => $cart->barang->harga
            ]);

            // Tandai barang sebagai sold out
            $cart->barang->update(['status_barang' => 'sold out']);
        }

        // Hapus cart
        Cart::whereIn('id', $validated['selected_cart_ids'])->delete();

        return response()->json([
            'message' => 'Transaksi berhasil disimpan',
            'transaksi' => $transaksi->load('detail.barang')
        ], 201);
    }
}
