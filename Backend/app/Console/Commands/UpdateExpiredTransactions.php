<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use Carbon\Carbon;

class UpdateExpiredTransactions extends Command
{
    protected $signature = 'transaksi:update-expired';
    protected $description = 'Update transaksi yang sudah hangus dan ubah status barang menjadi untuk donasi';

    public function handle()
    {
        $now = Carbon::now();

        $expiredItems = Barang::whereNull('tgl_pengambilan')
            ->where('status_transaksi', '!=', 'Hangus')
            ->whereDate('tgl_penitipan', '<=', $now->subDays(2))
            ->get();

        foreach ($expiredItems as $item) {
            $item->update([
                'status_transaksi' => 'Hangus',
                'status_barang' => 'Barang untuk Donasi',
            ]);
        }

        $this->info("Transaksi hangus berhasil diperbarui.");
    }
}
