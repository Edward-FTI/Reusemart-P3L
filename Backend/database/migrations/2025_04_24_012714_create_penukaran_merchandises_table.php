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
        Schema::create('penukaran_merchandises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pembeli');
            $table->unsignedBigInteger('id_merchandise'); // <- Pastikan ini ada!
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->dateTime('tanggal_penukaran');
            $table->integer('jumlah');
            $table->string('status');
            $table->timestamps();

            // Foreign keys (optional but recommended)
            $table->foreign('id_pembeli')->references('id')->on('pembelis');
            $table->foreign('id_merchandise')->references('id')->on('merchandises');
            $table->foreign('id_pegawai')->references('id')->on('pegawais');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penukaran_merchandises');
    }
};
