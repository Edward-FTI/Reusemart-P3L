<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan data jabatan secara manual
        DB::table('jabatans')->insert([
            [
                'role' => 'Admin',      // Ganti dengan role yang sesuai
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'Customer Service',    // Ganti dengan role yang sesuai
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'Pegawai Gudang',      // Ganti dengan role yang sesuai
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'Kurir', // Ganti dengan role yang sesuai
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan jabatan lainnya sesuai kebutuhan
        ]);
    }
}
