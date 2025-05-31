<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\DetailTransaksiPenjualan;
use App\Models\TransaksiPenjualan;

class Detail_transaksi_penjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_transaksi_penjualans')->insert([
            // Transaksi Penjualan ID 1 => Cart 1,2
            ['id_transaksi_penjualan' => 1, 'id_barang' => 1, 'harga_saat_transaksi' => 10000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_transaksi_penjualan' => 1, 'id_barang' => 2, 'harga_saat_transaksi' => 12000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_transaksi_penjualan' => 1, 'id_barang' => 3, 'harga_saat_transaksi' => 15000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            // Transaksi Penjualan ID 2 => Cart 3,4
            ['id_transaksi_penjualan' => 2, 'id_barang' => 3, 'harga_saat_transaksi' => 15000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_transaksi_penjualan' => 2, 'id_barang' => 4, 'harga_saat_transaksi' => 18000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Transaksi Penjualan ID 3 => Cart 5,6,7
            ['id_transaksi_penjualan' => 3, 'id_barang' => 5, 'harga_saat_transaksi' => 20000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_transaksi_penjualan' => 3, 'id_barang' => 6, 'harga_saat_transaksi' => 21000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_transaksi_penjualan' => 3, 'id_barang' => 7, 'harga_saat_transaksi' => 22000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Transaksi Penjualan ID 4 => Cart 8,9
            ['id_transaksi_penjualan' => 4, 'id_barang' => 8, 'harga_saat_transaksi' => 23000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_transaksi_penjualan' => 4, 'id_barang' => 9, 'harga_saat_transaksi' => 24000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Transaksi Penjualan ID 5 => Cart 10
            ['id_transaksi_penjualan' => 5, 'id_barang' => 10, 'harga_saat_transaksi' => 25000, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
