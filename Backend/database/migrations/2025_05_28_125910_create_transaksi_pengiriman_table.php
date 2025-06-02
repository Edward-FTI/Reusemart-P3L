<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_transaksi_penjualan');
            $table->unsignedBigInteger('id_pegawai');
            $table->dateTime('tgl_pengiriman')->nullable();
            $table->string('status_pengiriman')->default('Proses'); // Pending, Proses, Selesai
            $table->integer('biaya_pengiriman', 0, 100000);
            $table->text('catatan')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pengiriman');
    }
};
