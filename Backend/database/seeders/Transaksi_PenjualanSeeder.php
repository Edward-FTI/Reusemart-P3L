<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Transaksi_PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transaksi_penjualans')->insert([
            [
                'id_pengiriman' => 1,
                'id_pembeli' => 1,
                'total_harga_pembelian' => 500000,
                'alamat_pengiriman' => 'Jl. Kaliurang KM 7, Sleman, Yogyakarta',
                'ongkir' => 20000,
                'bukti_pembayaran' => 'bukti1.jpg',
                'id_pegawai' => 1,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 2,
                'id_pembeli' => 2,
                'total_harga_pembelian' => 750000,
                'alamat_pengiriman' => 'Jl. Parangtritis No. 88, Bantul, Yogyakarta',
                'ongkir' => 15000,
                'bukti_pembayaran' => 'bukti2.jpg',
                'id_pegawai' => 2,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 3,
                'id_pembeli' => 3,
                'total_harga_pembelian' => 320000,
                'alamat_pengiriman' => 'Jl. Magelang KM 5, Sleman, Yogyakarta',
                'ongkir' => 10000,
                'bukti_pembayaran' => 'bukti3.jpg',
                'id_pegawai' => 1,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 4,
                'id_pembeli' => 4,
                'total_harga_pembelian' => 900000,
                'alamat_pengiriman' => 'Jl. Wonosari No. 12, Gunungkidul, Yogyakarta',
                'ongkir' => 25000,
                'bukti_pembayaran' => 'bukti4.jpg',
                'id_pegawai' => 2,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengiriman' => 5,
                'id_pembeli' => 5,
                'total_harga_pembelian' => 450000,
                'alamat_pengiriman' => 'Jl. Malioboro No. 1, Kota Yogyakarta',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti5.jpg',
                'id_pegawai' => 3,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => 'terverifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
