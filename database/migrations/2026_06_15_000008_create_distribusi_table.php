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
        Schema::create('distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relawan_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penerima_id')->constrained('penerima_bantuan')->onDelete('cascade');
            $table->integer('jumlah_paket');
            $table->string('status')->default('pending'); // pending, dalam_proses, selesai, gagal
            $table->date('tanggal');
            $table->string('foto_bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi');
    }
};
