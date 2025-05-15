<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use DB;

class Transaksi_PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaksi_penjualans')->insert([
            [
                'id_pengiriman' => 1,
                'id_pembeli' => 1,
                'total_harga_pembelian' => 500000,
                'alamat_pengiriman' => 'Jl. Melati No. 10, Makassar',
                'ongkir' => 20000,
                'bukti_pembayaran' => 'bukti1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 2,
                'id_pembeli' => 2,
                'total_harga_pembelian' => 750000,
                'alamat_pengiriman' => 'Jl. Mawar No. 12, Yogyakarta',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 3,
                'id_pembeli' => 3,
                'total_harga_pembelian' => 320000,
                'alamat_pengiriman' => 'Jl. Cempaka No. 5, Bandung',
                'ongkir' => 15000,
                'bukti_pembayaran' => 'bukti3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 4,
                'id_pembeli' => 4,
                'total_harga_pembelian' => 900000,
                'alamat_pengiriman' => 'Jl. Kenanga No. 8, Surabaya',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti4.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 5,
                'id_pembeli' => 5,
                'total_harga_pembelian' => 450000,
                'alamat_pengiriman' => 'Jl. Flamboyan No. 20, Jakarta',
                'ongkir' => 20000,
                'bukti_pembayaran' => 'bukti5.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 6,
                'id_pembeli' => 6,
                'total_harga_pembelian' => 600000,
                'alamat_pengiriman' => 'Jl. Sakura No. 9, Denpasar',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti6.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 1,
                'id_pembeli' => 7,
                'total_harga_pembelian' => 800000,
                'alamat_pengiriman' => 'Jl. Teratai No. 11, Makassar',
                'ongkir' => 25000,
                'bukti_pembayaran' => 'bukti7.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 2,
                'id_pembeli' => 8,
                'total_harga_pembelian' => 1000000,
                'alamat_pengiriman' => 'Jl. Dahlia No. 7, Medan',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti8.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 3,
                'id_pembeli' => 9,
                'total_harga_pembelian' => 670000,
                'alamat_pengiriman' => 'Jl. Bougenville No. 13, Palembang',
                'ongkir' => 18000,
                'bukti_pembayaran' => 'bukti9.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 4,
                'id_pembeli' => 10,
                'total_harga_pembelian' => 550000,
                'alamat_pengiriman' => 'Jl. Anggrek No. 15, Semarang',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti10.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
