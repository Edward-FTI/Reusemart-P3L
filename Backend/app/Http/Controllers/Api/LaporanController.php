<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;

class LaporanController extends Controller
{
    public function laporanKategoriBarang()
    {
        $kategoriList = KategoriBarang::with('barangId')->get();

        $laporan = $kategoriList->map(function ($kategori) {
            $barangList = $kategori->barangId ?? collect(); // PERBAIKI disini

            return [
                'nama_kategori' => $kategori->nama_kategori,
                'terjual' => $barangList->filter(function ($b) {
                    return strtolower($b->status_barang) === 'terjual';
                })->count(),
                'gagal' => $barangList->filter(function ($b) {
                    return strtolower($b->status_barang) === 'barang untuk donasi';
                })->count(),
            ];
        });

        return response()->json($laporan);
    }
}
