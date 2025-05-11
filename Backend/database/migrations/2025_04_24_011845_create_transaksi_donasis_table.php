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
        Schema::create('transaksi_donasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_organisasi')->constrained('organisasis');
            $table->string('status')->default('pending');
            $table->string('nama_penitip');
            $table->string('jenis_barang');
            $table->string('jumlah_barang');
            $table->dateTime('tgl_transaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_donasis');
    }
};
