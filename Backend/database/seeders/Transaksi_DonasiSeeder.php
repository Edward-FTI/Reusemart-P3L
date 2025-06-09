<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Transaksi_DonasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transaksi_donasis')->insert([
            [
                'id_organisasi' => 1,
                'id_penitip' => 2,
                'id_barang' => 14,
                'nama_penerima' => 'Panti Asuhan Cahaya',
                'tgl_transaksi' => Carbon::create(2025, 6, rand(1, 10), rand(8, 18), rand(0, 59), rand(0, 59)),
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_organisasi' => 2,
                'id_penitip' => 2,
                'id_barang' => 15,
                'nama_penerima' => 'Yayasan Harapan Bangsa',
                'tgl_transaksi' => Carbon::create(2025, 6, rand(1, 10), rand(8, 18), rand(0, 59), rand(0, 59)),
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_organisasi' => 3,
                'id_penitip' => 2,
                'id_barang' => 16,
                'nama_penerima' => 'Panti Jompo Bahagia',
                'tgl_transaksi' => Carbon::create(2025, 6, rand(1, 10), rand(8, 18), rand(0, 59), rand(0, 59)),
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
