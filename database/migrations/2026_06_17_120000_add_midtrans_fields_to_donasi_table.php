<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->string('order_id')->nullable()->unique()->after('id');
            $table->string('snap_token')->nullable()->after('metode_pembayaran');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->string('midtrans_status')->nullable()->after('payment_type');
            $table->timestamp('paid_at')->nullable()->after('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
            $table->dropColumn([
                'order_id',
                'snap_token',
                'payment_type',
                'midtrans_status',
                'paid_at',
            ]);
        });
    }
};
