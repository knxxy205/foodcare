<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RiwayatStokPangan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_stok_pangan';

    protected $fillable = [
        'stok_pangan_id',
        'reference_type',
        'reference_id',
        'tipe',
        'jumlah_perubahan',
        'jumlah_sebelum',
        'jumlah_sesudah',
        'catatan',
    ];

    public function stokPangan(): BelongsTo
    {
        return $this->belongsTo(StokPangan::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}

