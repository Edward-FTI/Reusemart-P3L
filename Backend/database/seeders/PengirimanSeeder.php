<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_pengirimans')->insert([
            [
                'status_pengiriman' => 'sudah dikirim',
                'metode_pengiriman' => 'antar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'status_pengiriman' => 'belum dikirim',
                'metode_pengiriman' => 'ambil sendiri',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'status_pengiriman' => 'belum dikirim',
                'metode_pengiriman' => 'antar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'status_pengiriman' => 'sudah dikirim',
                'metode_pengiriman' => 'ambil sendiri',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'status_pengiriman' => 'sudah dikirim',
                'metode_pengiriman' => 'antar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'status_pengiriman' => 'belum dikirim',
                'metode_pengiriman' => 'ambil sendiri',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
