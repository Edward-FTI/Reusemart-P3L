<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembeliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan 10 data pembeli secara manual
        DB::table('pembelis')->insert([
            [
                'nama_pembeli' => 'Aliyah Putri',
                'email' => 'aliyah@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '081234567890',
                'point' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '082345678901',
                'point' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Citra Dewi',
                'email' => 'citra@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '083456789012',
                'point' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Dani Rahman',
                'email' => 'dani@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '084567890123',
                'point' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Erika Nasution',
                'email' => 'erika@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '085678901234',
                'point' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Fajar Hidayat',
                'email' => 'fajar@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '086789012345',
                'point' => 400,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Gina Lestari',
                'email' => 'gina@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '087890123456',
                'point' => 250,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Hendra Wijaya',
                'email' => 'hendra@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '088901234567',
                'point' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Indah Kurniawati',
                'email' => 'indah@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '089012345678',
                'point' => 180,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pembeli' => 'Joko Prabowo',
                'email' => 'joko@example.com',
                'password' => bcrypt('password123'),
                'no_hp' => '090123456789',
                'point' => 220,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
