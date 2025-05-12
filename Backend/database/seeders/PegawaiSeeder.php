<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan 10 data pegawai secara manual
        DB::table('pegawais')->insert([
            [
                'id_jabatan' => 1, // Menyesuaikan dengan ID Jabatan yang ada
                'nama' => 'Andi Sutrisno',
                'email' => 'andi.sutrisno@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 2,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 3,
                'nama' => 'Citra Wijaya',
                'email' => 'citra.wijaya@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 6000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 1,
                'nama' => 'Dina Pratama',
                'email' => 'dina.pratama@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 2,
                'nama' => 'Eko Susanto',
                'email' => 'eko.susanto@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 3,
                'nama' => 'Fani Lestari',
                'email' => 'fani.lestari@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5800000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 1,
                'nama' => 'Gina Hidayati',
                'email' => 'gina.hidayati@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 4900000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 2,
                'nama' => 'Hendra Prabowo',
                'email' => 'hendra.prabowo@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5600000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 3,
                'nama' => 'Irma Widya',
                'email' => 'irma.widya@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5900000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 1,
                'nama' => 'Joko Riyadi',
                'email' => 'joko.riyadi@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
