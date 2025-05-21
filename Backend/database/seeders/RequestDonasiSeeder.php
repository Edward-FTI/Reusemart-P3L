<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RequestDonasiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Request Donasi untuk Organisasi A (id = 1)
        DB::table('request__donasis')->insert([
            [
                'id_organisasi' => 1,
                'request' => 'Permintaan bantuan sembako untuk warga terdampak banjir',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_organisasi' => 1,
                'request' => 'Permintaan donasi buku untuk anak-anak kurang mampu',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_organisasi' => 1,
                'request' => 'Permintaan alat medis untuk puskesmas desa',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_organisasi' => 1,
                'request' => 'Permintaan makanan siap saji untuk korban kebakaran',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Request Donasi untuk Organisasi lainnya (id 2 sampai 10)
        $requests = [
            [2, 'Permintaan seragam sekolah untuk siswa kurang mampu'],
            [3, 'Permintaan perlengkapan ibadah untuk musala setempat'],
            [4, 'Permintaan kursi roda untuk warga difabel'],
            [5, 'Permintaan bantuan pangan untuk lansia'],
            [6, 'Permintaan susu dan vitamin anak'],
            [7, 'Permintaan peralatan olahraga untuk sekolah dasar'],
            [8, 'Permintaan peralatan kebersihan untuk lingkungan'],
            [9, 'Permintaan komputer bekas untuk pelatihan IT'],
            [10, 'Permintaan bahan bangunan untuk renovasi rumah warga'],
        ];

        foreach ($requests as [$id_organisasi, $request]) {
            DB::table('request__donasis')->insert([
                'id_organisasi' => $id_organisasi,
                'request' => $request,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
