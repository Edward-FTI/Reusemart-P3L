<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenitipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 10; $i++) {
            DB::table('penitips')->insert([
                'nama_penitip' => $faker->name,
                'no_ktp'       => $faker->nik(),
                'gambar_ktp'   => 'ktp_' . Str::random(5) . '.jpg',
                'saldo'        => $faker->numberBetween(100000, 5000000),
                'point'        => $faker->numberBetween(50, 500),
                'email'        => $faker->unique()->safeEmail,
                'password'     => Hash::make('password123'), // default password
                'badge'        => "Null",
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
