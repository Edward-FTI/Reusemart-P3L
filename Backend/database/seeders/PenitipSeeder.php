<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenitipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penitips = [
            [
                'nama_penitip' => 'Andi Sutrisno',
                'no_ktp'       => '7304010101010001',
                'alamat'       => 'Jl. Merdeka No. 12, Makassar',
                'gambar_ktp'   => 'ktp_andi.jpg',
                'saldo'        => 2500000,
                'point'        => 150,
                'email'        => 'andi.sutrisno@example.com',
            ],
            [
                'nama_penitip' => 'Penitip1',
                'no_ktp'       => '7304010202020002',
                'alamat'       => 'Jl. Perintis Kemerdekaan No. 5, Makassar',
                'gambar_ktp'   => 'ktp_budi.jpg',
                'saldo'        => 3100000,
                'point'        => 200,
                'email'        => 'penitip1@example.com',
            ],
            [
                'nama_penitip' => 'Citra Wijaya JR',
                'no_ktp'       => '7304010303030003',
                'alamat'       => 'Jl. Veteran Selatan No. 8, Makassar',
                'gambar_ktp'   => 'ktp_citra.jpg',
                'saldo'        => 2750000,
                'point'        => 180,
                'email'        => 'citra.wijayajr@example.com',
            ],
            [
                'nama_penitip' => 'Dina Pratama',
                'no_ktp'       => '7304010404040004',
                'alamat'       => 'Jl. Pengayoman No. 10, Makassar',
                'gambar_ktp'   => 'ktp_dina.jpg',
                'saldo'        => 2950000,
                'point'        => 190,
                'email'        => 'dina.pratama@example.com',
            ],
            [
                'nama_penitip' => 'Eko Susanto',
                'no_ktp'       => '7304010505050005',
                'alamat'       => 'Jl. Pettarani No. 20, Makassar',
                'gambar_ktp'   => 'ktp_eko.jpg',
                'saldo'        => 3200000,
                'point'        => 210,
                'email'        => 'eko.susanto@example.com',
            ],
            [
                'nama_penitip' => 'Fani Lestari',
                'no_ktp'       => '7304010606060006',
                'alamat'       => 'Jl. AP Pettarani No. 11, Makassar',
                'gambar_ktp'   => 'ktp_fani.jpg',
                'saldo'        => 2800000,
                'point'        => 175,
                'email'        => 'fani.lestari@example.com',
            ],
            [
                'nama_penitip' => 'Gina Hidayati',
                'no_ktp'       => '7304010707070007',
                'alamat'       => 'Jl. Urip Sumoharjo No. 22, Makassar',
                'gambar_ktp'   => 'ktp_gina.jpg',
                'saldo'        => 2650000,
                'point'        => 160,
                'email'        => 'gina.hidayati@example.com',
            ],
            [
                'nama_penitip' => 'Hendra Prabowo',
                'no_ktp'       => '7304010808080008',
                'alamat'       => 'Jl. Sultan Alauddin No. 30, Makassar',
                'gambar_ktp'   => 'ktp_hendra.jpg',
                'saldo'        => 3050000,
                'point'        => 220,
                'email'        => 'hendra.prabowo@example.com',
            ],
            [
                'nama_penitip' => 'Irma Widya',
                'no_ktp'       => '7304010909090009',
                'alamat'       => 'Jl. Hertasning No. 25, Makassar',
                'gambar_ktp'   => 'ktp_irma.jpg',
                'saldo'        => 2900000,
                'point'        => 195,
                'email'        => 'irma.widya@example.com',
            ],
            [
                'nama_penitip' => 'Joko Riyadi',
                'no_ktp'       => '7304011010100010',
                'alamat'       => 'Jl. Tamalanrea No. 13, Makassar',
                'gambar_ktp'   => 'ktp_joko.jpg',
                'saldo'        => 2700000,
                'point'        => 170,
                'email'        => 'joko.riyadi@example.com',
            ],
        ];

        foreach ($penitips as $penitip) {
            DB::table('penitips')->insert([
                'nama_penitip' => $penitip['nama_penitip'],
                'no_ktp'       => $penitip['no_ktp'],
                'alamat'       => $penitip['alamat'],
                'gambar_ktp'   => $penitip['gambar_ktp'],
                'saldo'        => $penitip['saldo'],
                'point'        => $penitip['point'],
                'email'        => $penitip['email'],
                'password'     => Hash::make('password123'),
                'password'     => Hash::make('password123'), // sama seperti di PegawaiSeeder
                'badge'        => 'Null',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
