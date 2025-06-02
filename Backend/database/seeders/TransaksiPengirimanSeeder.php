<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TransaksiPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksiIds = DB::table('transaksi_penjualans')->pluck('id');

        if ($transaksiIds->count() < 5) {
            $this->command->warn('Minimal dibutuhkan 5 transaksi_penjualans untuk seeder ini.');
            return;
        }

        // Ambil semua id pegawai
        $pegawaiIds = DB::table('pegawais')->pluck('id')->values();

        // Data hard-coded pertama (5 entri)
        $dataTetap = [
            [
                'id_transaksi_penjualan' => $transaksiIds[0],
                'id_pegawai' => 11,
                'tgl_pengiriman' => null,
                'status_pengiriman' => 'Proses',
                'biaya_pengiriman' => 22000,
                'catatan' => 'Menunggu konfirmasi pengiriman.',
            ],
            [
                'id_transaksi_penjualan' => $transaksiIds[1],
                'id_pegawai' => 12,
                'tgl_pengiriman' => null,
                'status_pengiriman' => 'Proses',
                'biaya_pengiriman' => 25000,
                'catatan' => 'Sedang dikemas.',
            ],
            [
                'id_transaksi_penjualan' => $transaksiIds[2],
                'id_pegawai' => 11,
                'tgl_pengiriman' => null,
                'status_pengiriman' => 'Selesai',
                'biaya_pengiriman' => 27000,
                'catatan' => 'Tiba di tujuan dengan aman.',
            ],
            [
                'id_transaksi_penjualan' => $transaksiIds[3],
                'id_pegawai' => 0,
                'tgl_pengiriman' => null,
                'status_pengiriman' => 'Proses',
                'biaya_pengiriman' => 24000,
                'catatan' => 'Belum ditugaskan ke pegawai.',
            ],
            [
                'id_transaksi_penjualan' => $transaksiIds[4],
                'id_pegawai' => 0,
                'tgl_pengiriman' => null,
                'status_pengiriman' => 'Proses',
                'biaya_pengiriman' => 26000,
                'catatan' => 'Dalam perjalanan.',
            ],
        ];

        foreach ($dataTetap as $row) {
            DB::table('transaksi_pengiriman')->insert(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Sisanya dibuat random
        $statusOps = [ 'Proses', 'Selesai'];
        for ($i = 5; $i < $transaksiIds->count(); $i++) {
            $idPegawai = $pegawaiIds->isEmpty() ? null : $pegawaiIds[rand(0, $pegawaiIds->count() - 1)];

            DB::table('transaksi_pengiriman')->insert([
                'id_transaksi_penjualan' => $transaksiIds[$i],
                'id_pegawai' => $idPegawai,
                'tgl_pengiriman' => Carbon::create(2025, 5, 25)->addDays($i),
                'status_pengiriman' => $statusOps[array_rand($statusOps)],
                'biaya_pengiriman' => rand(20000, 35000),
                'catatan' => rand(0, 1) ? 'Catatan pengiriman tersedia.' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
