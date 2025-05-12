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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penitip')->constrained('penitips');
            $table->foreignId('id_kategori')->constrained('kategoriBarang');
            $table->date('tgl_penitipan');
            $table->string('nama_barang');
            $table->double('harga_barang');
            $table->string('deskripsi');
            $table->string('status_garansi');
            $table->string('status_barang');
            $table->string('gambar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
