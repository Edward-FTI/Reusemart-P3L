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
                // 'id_pegawai' => 1,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pembeli' => 2,
                'total_harga_pembelian' => 750000,
                'alamat_pengiriman' => 'Jl. Parangtritis No. 88, Bantul, Yogyakarta',
                'ongkir' => 15000,
                'bukti_pembayaran' => 'bukti2.jpg',
                'status_pengiriman' => 'proses',
                'metode_pengiriman' => 'dikirim',
                // 'id_pegawai' => 2,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pembeli' => 3,
                'total_harga_pembelian' => 320000,
                'alamat_pengiriman' => 'Jl. Magelang KM 5, Sleman, Yogyakarta',
                'ongkir' => 10000,
                'bukti_pembayaran' => 'bukti3.jpg',
                'status_pengiriman' => 'selesai',
                'metode_pengiriman' => 'dikirim',
                // 'id_pegawai' => 1,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pembeli' => 4,
                'total_harga_pembelian' => 900000,
                'alamat_pengiriman' => 'Jl. Wonosari No. 12, Gunungkidul, Yogyakarta',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti4.jpg',
                'status_pengiriman' => 'proses',
                'metode_pengiriman' => 'diambil',
                // 'id_pegawai' => 2,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_pembeli' => 5,
                'total_harga_pembelian' => 450000,
                'alamat_pengiriman' => 'Jl. Malioboro No. 1, Kota Yogyakarta',
                'ongkir' => 0,
                'bukti_pembayaran' => 'bukti5.jpg',
                'status_pengiriman' => 'selesai',
                'metode_pengiriman' => 'diambil',
                // 'id_pegawai' => 3,
                'status_pembelian' => 'diproses',
                'verifikasi_pembayaran' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
