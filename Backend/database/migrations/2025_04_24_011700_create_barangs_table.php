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
            $table->foreignId('id_kategori')->constrained('kategori_barangs');
            $table->foreignId('id_pegawai')->constrained('pegawais');
            $table->foreignId('id_hunter')->nullable()->constrained('pegawais');
            $table->dateTime('tgl_penitipan');
            $table->dateTime('masa_penitipan');
            $table->integer('penambahan_durasi')->default(0);
            $table->string('nama_barang');
            $table->double('harga_barang');
            $table->integer('berat_barang');
            $table->string('deskripsi');
            $table->date('status_garansi')->nullable();
            $table->string('status_barang');
            $table->dateTime('tgl_pengambilan')->nullable();
            $table->string('gambar');
            $table->string('gambar_dua');
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
