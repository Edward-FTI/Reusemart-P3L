<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barangs')->insert([
            [
                'id_penitip' => 1,
                'id_kategori' => 1,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Acer Aspire Lite 14',
                'harga_barang' => 500000,
                'deskripsi' => 'Barang Oke',
                'status_garansi' => 'Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'acer.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 2,
                'id_kategori' => 2,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Pakaian Pria',
                'harga_barang' => 50000,
                'deskripsi' => 'Barang Oke',
                'status_garansi' => 'Tidak Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'pakaian.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 3,
                'id_kategori' => 3,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Meja TV',
                'harga_barang' => 150000,
                'deskripsi' => 'Kayu Jati',
                'status_garansi' => 'Ada',
                'status_barang' => 'Tersedia',
                'gambar' => 'meja.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 4,
                'id_kategori' => 4,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Buku Pelajaran',
                'harga_barang' => 20000,
                'deskripsi' => 'Halaman Lebih Banyak',
                'status_garansi' => 'Tidak Ada',
                'status_barang' => 'Tersedia',
                'gambar' => 'buku.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 5,
                'id_kategori' => 5,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Gitar',
                'harga_barang' => 250000,
                'deskripsi' => 'Gitar Listrik',
                'status_garansi' => 'Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'gitar.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 6,
                'id_kategori' => 6,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Botol Susu',
                'harga_barang' => 45000,
                'deskripsi' => 'Tahan Panas',
                'status_garansi' => 'Tidak Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'botolsusu.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 7,
                'id_kategori' => 7,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Sepeda Motor',
                'harga_barang' => 2500000,
                'deskripsi' => 'Mesin Oke',
                'status_garansi' => 'Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'motor.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 8,
                'id_kategori' => 8,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Tenda',
                'harga_barang' => 40000,
                'deskripsi' => 'Untuk Camping',
                'status_garansi' => 'Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'tenda.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 9,
                'id_kategori' => 9,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Printer',
                'harga_barang' => 400000,
                'deskripsi' => 'Free tinta 3 warna',
                'status_garansi' => 'Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'printer.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_penitip' => 10,
                'id_kategori' => 10,
                'tgl_penitipan' => now(),
                'nama_barang' => 'Parfum',
                'harga_barang' => 20000,
                'deskripsi' => 'Wangi Tahan Lama',
                'status_garansi' => 'Tidak Ada',
                'status_barang' => 'Dijual',
                'gambar' => 'parfum.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
