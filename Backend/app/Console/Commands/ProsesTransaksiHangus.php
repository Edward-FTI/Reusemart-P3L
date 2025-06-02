<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\PengambilanController;

class ProsesTransaksiHangus extends Command
{
    protected $signature = 'transaksi:hangus';
    protected $description = 'Mengubah status transaksi menjadi Hangus jika lewat 2 hari';

    public function handle()
    {
        $controller = new PengambilanController();
        $controller->prosesTransaksiHangusOtomatis();
        $this->info('Pengecekan transaksi hangus selesai.');
    }
}
