<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // password: admin123
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'Admin',
                'password' => 'admin123',
            ],
            // password: cs123
            [
                'name' => 'Customer Service User',
                'email' => 'customer_service@example.com',
                'role' => 'Customer Service',
                'password' => 'cs12345',
            ],
            // password: gudang123
            [
                'name' => 'Pegawai Gudang User',
                'email' => 'pegawai_gudang@example.com',
                'role' => 'Pegawai Gudang',
                'password' => 'gudang123',
            ],
            // password: kurir123
            [
                'name' => 'Kurir User',
                'email' => 'kurir@example.com',
                'role' => 'Kurir',
                'password' => 'kurir123',
            ],
            // password: penitip123
            [
                'name' => 'Penitip User',
                'email' => 'penitip@example.com',
                'role' => 'Penitip',
                'password' => 'penitip123',
            ],
            [
                'name' => 'Andi Sutrisno',
                'email' => 'andi.sutrisno@example.com',
                'role' => 'Penitip',
                'password' => 'password123',
            ],
            [
                'name' => 'Penitip1',
                'email' => 'penitip1@example.com',
                'role' => 'Penitip',
                'password' => 'password123',
            ],
            [
                'name' => 'Pembeli User',
                'email' => 'pembeli@example.com',
                'role' => 'Pembeli',
                'password' => 'pembeli123',
            ],
            [
                'name' => 'Organisasi User',
                'email' => 'organisasi@example.com',
                'role' => 'Organisasi',
                'password' => 'organisasi123',
            ],
            [
                'name' => 'Organisasi A',
                'email' => 'organisasi_a@example.com',
                'role' => 'Organisasi',
                'password' => 'organisasi123',
            ],
            [
                'name' => 'Owner User',
                'email' => 'owner@example.com',
                'role' => 'Owner',
                'password' => 'owner123',
            ],
            [
                'name' => 'Hunter User',
                'email' => 'hunter@example.com',
                'role' => 'Hunter',
                'password' => 'hunter123',
            ],
            [
                'name' => 'Generic User',
                'email' => 'user@example.com',
                'role' => 'Pembeli',
                'password' => 'user123',
            ],
            [
                'name' => 'Citra Wijay',
                'email' => 'citra.wijaya@example.com',
                'role' => 'Pegawai Gudang',
                'password' => 'password123',
            ],
            [
                'name' => 'Fani Lestari',
                'email' => 'fani.lestari@example.com',
                'role' => 'Pegawai Gudang',
                'password' => 'password123',
            ],
            [
                'name' => 'Irma Widya',
                'email' => 'irma.widya@example.com',
                'role' => 'Pegawai Gudang',
                'password' => 'password123',
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'password' => Hash::make($user['password']),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tambahkan user dari tabel organisasis
        $organisasiList = DB::table('organisasis')->get();
        foreach ($organisasiList as $org) {
            $exists = User::where('email', $org->email)->exists();
            if (!$exists) {
                User::create([
                    'name' => $org->nama,
                    'email' => $org->email,
                    'role' => 'Organisasi',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Tambahkan user dari data pembeli manual
        $pembelis = [
            ['nama_pembeli' => 'Aliyah Putri', 'email' => 'aliyah@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Budi Santoso', 'email' => 'budi@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Citra Dewi', 'email' => 'citra@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Dani Rahman', 'email' => 'dani@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Erika Nasution', 'email' => 'erika@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Fajar Hidayat', 'email' => 'fajar@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Gina Lestari', 'email' => 'gina@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Hendra Wijaya', 'email' => 'hendra@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Indah Kurniawati', 'email' => 'indah@example.com', 'password' => 'password123'],
            ['nama_pembeli' => 'Joko Prabowo', 'email' => 'joko@example.com', 'password' => 'password123'],
        ];

        foreach ($pembelis as $pembeli) {
            User::firstOrCreate(
                ['email' => $pembeli['email']],
                [
                    'name' => $pembeli['nama_pembeli'],
                    'role' => 'Pembeli',
                    'password' => Hash::make($pembeli['password']),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
