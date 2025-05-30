<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('carts')->insert([
            // Transaksi Penjualan ID 1
            ['id_pembeli' => 1, 'id_barang' => 1],
            ['id_pembeli' => 1, 'id_barang' => 2],

            // Transaksi Penjualan ID 2
            ['id_pembeli' => 2, 'id_barang' => 3],
            ['id_pembeli' => 2, 'id_barang' => 4],

            // Transaksi Penjualan ID 3
            ['id_pembeli' => 3, 'id_barang' => 5],
            ['id_pembeli' => 3, 'id_barang' => 6],
            ['id_pembeli' => 3, 'id_barang' => 7],

            // Transaksi Penjualan ID 4
            ['id_pembeli' => 4, 'id_barang' => 8],
            ['id_pembeli' => 4, 'id_barang' => 9],

            // Transaksi Penjualan ID 5
            ['id_pembeli' => 5, 'id_barang' => 10],
        ]);
    }
}
