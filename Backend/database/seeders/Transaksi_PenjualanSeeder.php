<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Transaksi_PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transaksi_penjualans')->insert([
            [
                'id_pembeli' => 1,
                'total_harga_pembelian' => 500000,
                'alamat_pengiriman' => 'Jl. Kaliurang KM 7, Sleman, Yogyakarta',
                'ongkir' => 20000,
                'bukti_pembayaran' => 'bukti1.jpg',
                'status_pengiriman' => 'proses',
                'metode_pengiriman' => 'dikirim',
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'tgl_transaksi' => Carbon::now()->addDays(4),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'id_pembeli' => 2,
                'total_harga_pembelian' => 750000,
                'alamat_pengiriman' => 'Jl. Parangtritis No. 88, Bantul, Yogyakarta',
                'ongkir' => 15000,
                'bukti_pembayaran' => 'bukti2.jpg',
                'status_pengiriman' => 'proses',
                'metode_pengiriman' => 'dikirim',
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'tgl_transaksi' => Carbon::now()->addDays(6),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'id_pembeli' => 3,
                'total_harga_pembelian' => 320000,
                'alamat_pengiriman' => 'Jl. Magelang KM 5, Sleman, Yogyakarta',
                'ongkir' => 10000,
                'bukti_pembayaran' => 'bukti3.jpg',
                'status_pengiriman' => 'selesai',
                'metode_pengiriman' => 'dikirim',
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'tgl_transaksi' => Carbon::now()->addDays(14),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id_pembeli' => 4,
                'total_harga_pembelian' => 900000,
                'alamat_pengiriman' => 'Jl. Wonosari No. 12, Gunungkidul, Yogyakarta',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti4.jpg',
                'status_pengiriman' => 'proses',
                'metode_pengiriman' => 'diambil',
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'tgl_transaksi' => Carbon::now()->addDays(32),
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'id_pembeli' => 4,
                'total_harga_pembelian' => 450000,
                'alamat_pengiriman' => 'Jl. Malioboro No. 1, Kota Yogyakarta',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti5.jpg',
                'status_pengiriman' => 'selesai',
                'metode_pengiriman' => 'dikirim',
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'tgl_transaksi' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // untuk laporan
            [
                'id_pembeli' => 6,
                'total_harga_pembelian' => 2517000,
                'alamat_pengiriman' => 'Jl. Malioboro No. 1, Kota Yogyakarta',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti5.jpg',
                'status_pengiriman' => 'selesai',
                'metode_pengiriman' => 'diambil',
                'status_pembelian' => 'selesai',
                'verifikasi_pembayaran' => 'terverifikasi',
                'tgl_transaksi' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],

            [
                'id_pembeli' => 7,
                'total_harga_pembelian' => 5000000,
                'alamat_pengiriman' => 'Corongan',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti6.jpg',
                'status_pengiriman' => 'selesai',
                'metode_pengiriman' => 'diambil',
                'status_pembelian' => 'selesai',
                'verifikasi_pembayaran' => 'terverifikasi',
                'tgl_transaksi' => Carbon::now()->subDays(27),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
        ]);
    }
}
