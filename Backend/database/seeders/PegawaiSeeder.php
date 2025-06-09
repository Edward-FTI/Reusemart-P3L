<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pegawais')->insert([
            [
                'id_jabatan' => 1,
                'nama' => 'Andi Sutrisno',
                'tgl_lahir' => '1990-01-15',
                'email' => 'andi.sutrisno@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 2,
                'nama' => 'Budi Santoso',
                'tgl_lahir' => '1988-04-20',
                'email' => 'budi.santoso@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 3,
                'nama' => 'Citra Wijaya',
                'tgl_lahir' => '1992-07-10',
                'email' => 'citra.wijaya@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 6000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 1,
                'nama' => 'Dina Pratama',
                'tgl_lahir' => '1991-03-05',
                'email' => 'dina.pratama@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 2,
                'nama' => 'Eko Susanto',
                'tgl_lahir' => '1989-11-22',
                'email' => 'eko.susanto@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 2,
                'nama' => 'Heru',
                'tgl_lahir' => '1989-12-10',
                'email' => 'heru@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 3,
                'nama' => 'Fani Lestari',
                'tgl_lahir' => '1993-09-14',
                'email' => 'fani.lestari@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5800000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 1,
                'nama' => 'Gina Hidayati',
                'tgl_lahir' => '1990-12-01',
                'email' => 'gina.hidayati@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 4900000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 2,
                'nama' => 'Hendra Prabowo',
                'tgl_lahir' => '1987-08-19',
                'email' => 'hendra.prabowo@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5600000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 3,
                'nama' => 'Irma Widya',
                'tgl_lahir' => '1992-06-30',
                'email' => 'irma.widya@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5900000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 1,
                'nama' => 'Joko Riyadi',
                'tgl_lahir' => '1991-02-25',
                'email' => 'joko.riyadi@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_jabatan' => 4,
                'nama' => 'Kiki Ramadhan',
                'tgl_lahir' => '1994-05-12',
                'email' => 'kiki.ramadhan@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5700000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_jabatan' => 4,
                'nama' => 'Lina Anggraini',
                'tgl_lahir' => '1990-10-03',
                'email' => 'lina.anggraini@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5900000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_jabatan' => 5,
                'nama' => 'Susanto',
                'tgl_lahir' => '2020-12-10',
                'email' => 'susanto@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_jabatan' => 5,
                'nama' => 'Andi Suharto',
                'tgl_lahir' => '2020-12-10',
                'email' => 'andi@example.com',
                'password' => bcrypt('password123'),
                'gaji' => 5100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
