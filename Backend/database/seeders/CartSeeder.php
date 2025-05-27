<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('carts')->insert([
            [
                'id_pembeli' => 1,
                'id_barang' => 1,
                'id_transaksi_penjualan' => 1, // milik transaksi id 1
            ],
            [
                'id_pembeli' => 1,
                'id_barang' => 2,
                'id_transaksi_penjualan' => 1,
            ],
            [
                'id_pembeli' => 2,
                'id_barang' => 3,
                'id_transaksi_penjualan' => 2,
            ],
            [
                'id_pembeli' => 2,
                'id_barang' => 4,
                'id_transaksi_penjualan' => 2,
            ],
            [
                'id_pembeli' => 3,
                'id_barang' => 5,
                'id_transaksi_penjualan' => 3,
            ],
            [
                'id_pembeli' => 3,
                'id_barang' => 6,
                'id_transaksi_penjualan' => 3,
            ],
            [
                'id_pembeli' => 3,
                'id_barang' => 7,
                'id_transaksi_penjualan' => null, // belum checkout
            ],
        ]);
    }
}
