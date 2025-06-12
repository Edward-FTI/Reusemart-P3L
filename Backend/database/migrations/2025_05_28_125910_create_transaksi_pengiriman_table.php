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
        // Schema::create('transaksi_pengiriman', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('id_transaksi_penjualan')->constrained('transaksi_penjualans');
        //     $table->unsignedBigInteger('id_pegawai')->nullable();
        //     $table->foreignId('id_pegawai')->constrained('pegawais');
        //     $table->dateTime('tgl_pengiriman')->nullable();
        //     $table->string('status_pengiriman')->default('Proses'); // Pending, Proses, Selesai
        //     $table->integer('biaya_pengiriman', 0, 100000);
        //     $table->text('catatan')->nullable();
        //     $table->timestamps();

        // });
        Schema::create('transaksi_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_transaksi_penjualan');
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->dateTime('tgl_pengiriman')->nullable();
            $table->string('status_pengiriman')->default('Proses');
            $table->unsignedInteger('biaya_pengiriman');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_transaksi_penjualan')->references('id')->on('transaksi_penjualans')->onDelete('cascade');
            $table->foreign('id_pegawai')->references('id')->on('pegawais')->onDelete('set null');
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
