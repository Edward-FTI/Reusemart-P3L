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

        // ✅ Tambahan request sesuai transaksi donasi (status = 'selesai')
        DB::table('request_donasis')->insert([
            [
                'id_organisasi' => 1, // Organisasi A
                'request'       => 'Permintaan donasi tas sekolah untuk anak-anak kurang mampu',
                'status'        => 'selesai',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_organisasi' => 2, // Organisasi B
                'request'       => 'Permintaan jaket hangat untuk anak yatim piatu',
                'status'        => 'selesai',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_organisasi' => 3, // Organisasi C
                'request'       => 'Permintaan buku cerita anak untuk taman baca masyarakat',
                'status'        => 'selesai',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);

        // ✅ Tetap insert semua request lama dengan status = 'pending'
        DB::table('request_donasis')->insert([
            [
                'id_organisasi' => 1,
                'request'       => 'Permintaan bantuan sembako untuk warga terdampak banjir',
                'status'        => 'pending',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_organisasi' => 1,
                'request'       => 'Permintaan donasi buku untuk anak-anak kurang mampu',
                'status'        => 'pending',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_organisasi' => 1,
                'request'       => 'Permintaan alat medis untuk puskesmas desa',
                'status'        => 'pending',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_organisasi' => 1,
                'request'       => 'Permintaan makanan siap saji untuk korban kebakaran',
                'status'        => 'pending',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);

        // ✅ Request Donasi untuk Organisasi lainnya (id 2 sampai 10) → status 'pending'
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
            DB::table('request_donasis')->insert([
                'id_organisasi' => $id_organisasi,
                'request'       => $request,
                'status'        => 'pending',
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
