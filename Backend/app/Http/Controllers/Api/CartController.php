<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class CartController extends Controller
{
    // Ambil id_pembeli dari email user yang login
    private function getPembeliId()
    {
        $userEmail = Auth::user()->email;
        $pembeli = Pembeli::where('email', $userEmail)->first();

        if (!$pembeli) {
            return null;
        }

        return $pembeli->id;
    }

    // Menampilkan semua cart milik user yang login
    public function index()
{
    try {
        $pembeliId = $this->getPembeliId();

        if (!$pembeliId) {
            return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
        }

        // Ambil semua cart milik user
        $carts = Cart::where('id_pembeli', $pembeliId)->with('barang')->get();

        // Hapus item yang barangnya sudah sold out
        foreach ($carts as $cart) {
            if (strtolower($cart->barang->status_barang) === 'sold out') {
                $cart->delete();
            }
        }

        // Ambil ulang cart setelah penghapusan
        $updatedCarts = Cart::where('id_pembeli', $pembeliId)->with('barang')->get();

        return response()->json([
            'message' => 'Data cart berhasil diambil',
            'data' => $updatedCarts
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Gagal mengambil data cart',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // Menambahkan item ke cart
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|exists:barangs,id',
        ]);

        try {
            $pembeliId = $this->getPembeliId();

            if (!$pembeliId) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
            }

            $cart = Cart::create([
                'id_pembeli' => $pembeliId,
                'id_barang' => $validatedData['id_barang'],
            ]);

            return response()->json([
                'message' => 'Item berhasil ditambahkan ke cart',
                'data' => $cart
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan item ke cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menampilkan satu item di cart
    public function show($id)
    {
        try {
            $pembeliId = $this->getPembeliId();

            if (!$pembeliId) {
                return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
            }

            $cart = Cart::where('id_pembeli', $pembeliId)->where('id', $id)->with('barang')->first();

            if (!$cart) {
                return response()->json(['message' => 'Item cart tidak ditemukan atau Anda tidak memiliki izin'], 404);
            }

            return response()->json([
                'message' => 'Item cart berhasil diambil',
                'data' => $cart
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil item cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menghapus item dari cart
public function destroy($id)
{
    try {
        $pembeliId = $this->getPembeliId();

        if (!$pembeliId) {
            return response()->json(['message' => 'Data pembeli tidak ditemukan untuk user yang login'], 404);
        }

        $cart = Cart::where('id_pembeli', $pembeliId)->where('id', $id)->with('barang')->first();

        if (!$cart) {
            return response()->json(['message' => 'Item cart tidak ditemukan atau Anda tidak memiliki izin'], 404);
        }

        // Cek status_barang apakah 'sold out' (case-insensitive)
        if (strtolower($cart->barang->status_barang) === 'sold out') {
            $cart->delete();

            return response()->json([
                'message' => 'Item cart dihapus karena barang sudah sold out',
                'data' => $cart
            ]);
        }

        // Jika tidak sold out, tetap bisa dihapus manual
        $cart->delete();

        return response()->json(['message' => 'Item cart berhasil dihapus']);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Gagal menghapus item cart',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function updateTransaksi(Request $request, $id)
{
    try {
        $pembeliId = $this->getPembeliId();

        if (!$pembeliId) {
            return response()->json(['message' => 'Data pembeli tidak ditemukan'], 404);
        }

        $cart = Cart::where('id_pembeli', $pembeliId)->where('id', $id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Item cart tidak ditemukan atau tidak memiliki izin'], 404);
        }

        // Ambil nilai dari frontend (misalnya boolean atau angka)
        $status = $request->input('checked');

        // Ubah jadi 1 atau null
        $cart->id_transaksi_penjualan = $status ? 1 : null;
        $cart->save();

        return response()->json([
            'message' => 'Status transaksi cart berhasil diperbarui',
            'data' => $cart
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Gagal memperbarui status transaksi',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
