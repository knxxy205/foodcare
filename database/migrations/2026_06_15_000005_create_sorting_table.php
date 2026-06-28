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
        Schema::create('sorting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relawan_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('stok_id')->constrained('stok_pangan')->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamp('waktu_proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sorting');
    }
};
