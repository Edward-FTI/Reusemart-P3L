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
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembeli')->constrained('pembelis');
            $table->double('total_harga_pembelian');
            $table->string('metode_pengiriman');
            $table->string('alamat_pengiriman')->nullable();
            $table->double('ongkir');
            $table->text('bukti_pembayaran')->nullable();
            $table->string('status_pengiriman');
            // $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->string('status_pembelian');
            $table->string('verifikasi_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualans');
    }
};
