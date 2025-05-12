<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan 10 data organisasi secara manual
        DB::table('organisasis')->insert([
            [
                'nama' => 'Organisasi A',
                'alamat' => 'Jalan Raya No. 1, Jakarta',
                'permintaan' => 'Bantuan untuk kegiatan sosial',
                'email' => 'organisasi_a@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi B',
                'alamat' => 'Jalan Merdeka No. 2, Bandung',
                'permintaan' => 'Penggalangan dana untuk pendidikan',
                'email' => 'organisasi_b@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '082345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi C',
                'alamat' => 'Jalan Sudirman No. 3, Surabaya',
                'permintaan' => 'Bantuan untuk lingkungan hidup',
                'email' => 'organisasi_c@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '083456789012',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi D',
                'alamat' => 'Jalan Sisingamangaraja No. 4, Medan',
                'permintaan' => 'Donasi untuk penyediaan makanan',
                'email' => 'organisasi_d@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '084567890123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi E',
                'alamat' => 'Jalan Gajah Mada No. 5, Yogyakarta',
                'permintaan' => 'Pendanaan untuk penelitian kesehatan',
                'email' => 'organisasi_e@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '085678901234',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi F',
                'alamat' => 'Jalan Palmera No. 6, Makassar',
                'permintaan' => 'Bantuan untuk pengentasan kemiskinan',
                'email' => 'organisasi_f@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '086789012345',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi G',
                'alamat' => 'Jalan Merpati No. 7, Denpasar',
                'permintaan' => 'Sosialisasi mengenai pentingnya pendidikan',
                'email' => 'organisasi_g@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '087890123456',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi H',
                'alamat' => 'Jalan Kebon Jeruk No. 8, Bali',
                'permintaan' => 'Donasi untuk anak-anak yatim piatu',
                'email' => 'organisasi_h@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '088901234567',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi I',
                'alamat' => 'Jalan Pahlawan No. 9, Semarang',
                'permintaan' => 'Program peningkatan keterampilan pemuda',
                'email' => 'organisasi_i@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '089012345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Organisasi J',
                'alamat' => 'Jalan Raya No. 10, Solo',
                'permintaan' => 'Bantuan untuk penyediaan air bersih',
                'email' => 'organisasi_j@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '090123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
