<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
        {
            Schema::create('carts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_pembeli')->constrained('pembelis')->onDelete('cascade');
        $table->foreignId('id_barang')->constrained('barangs')->onDelete('cascade');
        $table->foreignId('id_transaksi_penjualan')->nullable()->constrained('transaksi_penjualans')->onDelete('set null');
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
    
};