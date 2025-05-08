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
        Schema::create('transaksi_penitipans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penitip')->constrained('penitips');
            $table->dateTime('tgl_penitipan');
            $table->string('status_penitipan');
            $table->integer('durasi_penitipan');
            $table->date('batas_akhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penitipans');
    }
};
