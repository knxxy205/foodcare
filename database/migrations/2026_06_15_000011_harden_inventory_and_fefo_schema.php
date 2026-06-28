<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_pangan', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_awal')->default(0)->after('nama_barang');
            $table->unsignedInteger('jumlah_tersedia')->default(0)->after('jumlah_awal');
        });

        DB::table('stok_pangan')->update([
            'jumlah_awal' => DB::raw('jumlah'),
            'jumlah_tersedia' => DB::raw('jumlah'),
        ]);

        Schema::table('distribusi_stok_pangan', function (Blueprint $table) {
            $table->string('nama_barang')->nullable()->after('stok_pangan_id');
            $table->date('tanggal_kadaluarsa')->nullable()->after('jumlah');
        });

        Schema::create('riwayat_stok_pangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_pangan_id')->constrained('stok_pangan')->cascadeOnDelete();
            $table->nullableMorphs('reference');
            $table->string('tipe');
            $table->integer('jumlah_perubahan');
            $table->unsignedInteger('jumlah_sebelum');
            $table->unsignedInteger('jumlah_sesudah');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok_pangan');

        Schema::table('distribusi_stok_pangan', function (Blueprint $table) {
            $table->dropColumn(['nama_barang', 'tanggal_kadaluarsa']);
        });

        Schema::table('stok_pangan', function (Blueprint $table) {
            $table->dropColumn(['jumlah_awal', 'jumlah_tersedia']);
        });
    }
};

