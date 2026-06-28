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
        Schema::create('simulasi_gudang', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_barang');
            $table->integer('jumlah_relawan');
            $table->integer('kapasitas_sorting');
            $table->integer('kapasitas_packaging');
            $table->string('status_bottleneck'); // Bottleneck, Optimal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulasi_gudang');
    }
};
