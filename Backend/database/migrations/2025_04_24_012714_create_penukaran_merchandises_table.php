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
            $table->foreignId('id_pembeli')->constrained('pembelis');
            $table->foreignId('id_merhandise')->constrained('merchandises');
            $table->foreignId('id_pegawai')->constrained('pegawais');
            $table->date('tanggal_penukaran');
            $table->string('status');
            $table->integer('jumlah');
            $table->timestamps();
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
