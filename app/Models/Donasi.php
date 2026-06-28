<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\DonationService;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';

    protected $fillable = [
        'order_id',
        'user_id',
        'program_id',
        'jumlah',
        'metode_pembayaran',
        'snap_token',
        'payment_type',
        'midtrans_status',
        'status',
        'tanggal',
        'paid_at',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'paid_at' => 'datetime',
        'jumlah' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramDonasi::class, 'program_id');
    }

    protected static function booted(): void
    {
        static::saved(function (Donasi $donasi): void {
            app(DonationService::class)->syncProgramTotal($donasi->program);
        });

        static::deleted(function (Donasi $donasi): void {
            app(DonationService::class)->syncProgramTotal($donasi->program);
        });
    }
}
