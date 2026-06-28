<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sorting extends Model
{
    use HasFactory;

    protected $table = 'sorting';

    protected $fillable = [
        'relawan_id',
        'stok_id',
        'jumlah',
        'waktu_proses',
    ];

    protected $casts = [
        'waktu_proses' => 'datetime',
    ];

    public function relawan()
    {
        return $this->belongsTo(User::class, 'relawan_id');
    }

    public function stok()
    {
        return $this->belongsTo(StokPangan::class, 'stok_id');
    }
}
