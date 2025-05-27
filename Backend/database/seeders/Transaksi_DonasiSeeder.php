<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiDonasiSeeder extends Seeder
{
    public function run()
    {
        DB::table('transaksi_donasis')->insert([
            [
                'id_organisasi'   => 1,
                'status'          => 'pending',
                'nama_penitip'    => 'Andi Saputra',
                'jenis_barang'    => 'Pakaian',
                'jumlah_barang'   => 10,
                'tgl_transaksi'   => '2025-05-01',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 2,
                'status'          => 'selesai',
                'nama_penitip'    => 'Rina Maulida',
                'jenis_barang'    => 'Makanan',
                'jumlah_barang'   => 25,
                'tgl_transaksi'   => '2025-05-02',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 3,
                'status'          => 'pending',
                'nama_penitip'    => 'Budi Hartono',
                'jenis_barang'    => 'Mainan',
                'jumlah_barang'   => 15,
                'tgl_transaksi'   => '2025-05-03',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 4,
                'status'          => 'dibatalkan',
                'nama_penitip'    => 'Siti Aisyah',
                'jenis_barang'    => 'Buku',
                'jumlah_barang'   => 8,
                'tgl_transaksi'   => '2025-05-04',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 5,
                'status'          => 'selesai',
                'nama_penitip'    => 'Dedi Prasetyo',
                'jenis_barang'    => 'Pakaian',
                'jumlah_barang'   => 20,
                'tgl_transaksi'   => '2025-05-05',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 1,
                'status'          => 'pending',
                'nama_penitip'    => 'Lina Oktaviani',
                'jenis_barang'    => 'Makanan',
                'jumlah_barang'   => 18,
                'tgl_transaksi'   => '2025-05-06',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 2,
                'status'          => 'selesai',
                'nama_penitip'    => 'Agus Santoso',
                'jenis_barang'    => 'Mainan',
                'jumlah_barang'   => 12,
                'tgl_transaksi'   => '2025-05-07',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 3,
                'status'          => 'dibatalkan',
                'nama_penitip'    => 'Nur Aini',
                'jenis_barang'    => 'Buku',
                'jumlah_barang'   => 30,
                'tgl_transaksi'   => '2025-05-08',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 4,
                'status'          => 'pending',
                'nama_penitip'    => 'Rizky Hidayat',
                'jenis_barang'    => 'Pakaian',
                'jumlah_barang'   => 22,
                'tgl_transaksi'   => '2025-05-09',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'id_organisasi'   => 5,
                'status'          => 'selesai',
                'nama_penitip'    => 'Wulan Sari',
                'jenis_barang'    => 'Makanan',
                'jumlah_barang'   => 27,
                'tgl_transaksi'   => '2025-05-10',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
        ]);
    }
}
