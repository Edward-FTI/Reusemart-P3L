<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
                'role' => 'admin',
                'password' => 'admin123',
            ],
            // password: cs123
            [
                'name' => 'Customer Service User',
                'email' => 'customer_service@example.com',
                'role' => 'customer_service',
                'password' => 'cs123',
            ],
            // password: gudang123
            [
                'name' => 'Pegawai Gudang User',
                'email' => 'pegawai_gudang@example.com',
                'role' => 'pegawai_gudang',
                'password' => 'gudang123',
            ],
            // password: kurir123
            [
                'name' => 'Kurir User',
                'email' => 'kurir@example.com',
                'role' => 'kurir',
                'password' => 'kurir123',
            ],
            // password: penitip123
            [
                'name' => 'Penitip User',
                'email' => 'penitip@example.com',
                'role' => 'penitip',
                'password' => 'penitip123',
            ],
            // password: pembeli123
            [
                'name' => 'Pembeli User',
                'email' => 'pembeli@example.com',
                'role' => 'pembeli',
                'password' => 'pembeli123',
            ],
            // password: organisasi123
            [
                'name' => 'Organisasi User',
                'email' => 'organisasi@example.com',
                'role' => 'organisasi',
                'password' => 'organisasi123',
            ],
            // password: owner123
            [
                'name' => 'Owner User',
                'email' => 'owner@example.com',
                'role' => 'owner',
                'password' => 'owner123',
            ],
            // password: hunter123
            [
                'name' => 'Hunter User',
                'email' => 'hunter@example.com',
                'role' => 'hunter',
                'password' => 'hunter123',
            ],
            // password: user123
            [
                'name' => 'Generic User',
                'email' => 'user@example.com',
                'role' => 'pembeli',
                'password' => 'user123',
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
    }
}
