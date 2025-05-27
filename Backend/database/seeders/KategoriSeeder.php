<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori_barangs')->insert([
            [
                'nama_kategori' => 'Elektronik & Gadget',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => 'Pakaian & Aksesori',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => 'Perabotan Rumah Tangga',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => ' Buku, Alat Tulis, & Peralatan Sekolah',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => 'Hobi, Mainan, & Koleksi',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => ' Perlengkapan Bayi & Anak',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => 'Otomotif & Aksesori',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => 'Perlengkapan Taman & Outdoor',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => 'Peralatan Kantor & Industri',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_kategori' => 'Kosmetik & Perawatan Diri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
