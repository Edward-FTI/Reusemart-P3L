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
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_pegawai');
            $table->dateTime('tgl_pengiriman');
            $table->string('status_pengiriman')->default('Pending'); // Pending, Proses, Selesai
            $table->string('alamat_pengiriman');
            $table->decimal('biaya_pengiriman', 10, 2);
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
