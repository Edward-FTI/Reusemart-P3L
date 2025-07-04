<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penitips', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_penitip');
            $table->string('no_ktp');
            $table->string('alamat');
            $table->string('gambar_ktp');
            $table->double('saldo')->nullable()->default(000.00);
            $table->integer('point');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('badge')->default("-");
            $table->double('nominalTarik')->default(0.0);
            // $table->double('nominalTarik')->nullable()->default(500000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penitips');
    }
};
