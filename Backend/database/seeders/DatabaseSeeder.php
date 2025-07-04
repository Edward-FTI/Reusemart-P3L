<?php

namespace Database\Seeders;

use App\Models\Organisasi;
use App\Models\Pegawai;
use App\Models\TransaksiDonasi;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            UserSeeder::class,
            PenitipSeeder::class,
            JabatanSeeder::class,
            OrganisasiSeeder::class,
            PegawaiSeeder::class,
            PembeliSeeder::class,
            PengirimanSeeder::class,
            RequestDonasiSeeder::class,
            KategoriSeeder::class,
            BarangSeeder::class,
            CartSeeder ::class,
            Transaksi_PenjualanSeeder::class,
            Detail_transaksi_penjualanSeeder ::class,
            TransaksiPengirimanSeeder::class,
            Transaksi_DonasiSeeder::class,
            MerchandiseSeeder::class,

        ]);
    }
}
