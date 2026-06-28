<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokPangan extends Model
{
    use HasFactory;

    protected $table = 'stok_pangan';

    protected $fillable = [
        'nama_barang',
        'jumlah',
        'jumlah_awal',
        'jumlah_tersedia',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'jumlah_awal' => 'integer',
        'jumlah_tersedia' => 'integer',
        'tanggal_masuk' => 'date',
        'tanggal_kadaluarsa' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (StokPangan $stok): void {
            $initialQuantity = (int) ($stok->jumlah_awal ?: $stok->jumlah ?: 0);

            if (!$stok->jumlah_awal) {
                $stok->jumlah_awal = $initialQuantity;
            }

            if (!$stok->jumlah) {
                $stok->jumlah = $stok->jumlah_awal;
            }

            if ($stok->jumlah_tersedia === null || (!$stok->exists && $stok->jumlah_tersedia === 0)) {
                $stok->jumlah_tersedia = $stok->jumlah_awal;
            }
        });
    }

    public function sortings()
    {
        return $this->hasMany(Sorting::class, 'stok_id');
    }

    public function packagings()
    {
        return $this->hasMany(Packaging::class, 'stok_id');
    }

    public function distribusis()
    {
        return $this->belongsToMany(Distribusi::class, 'distribusi_stok_pangan', 'stok_pangan_id', 'distribusi_id')
                    ->withPivot('jumlah', 'nama_barang', 'tanggal_kadaluarsa')
                    ->withTimestamps();
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatStokPangan::class);
    }

    // Accessors for stock status
    public function getRawStockAttribute(): int
    {
        $sorted = $this->sortings()->sum('jumlah');
        return max(0, $this->jumlah_awal - $sorted);
    }

    public function getSortedStockAttribute(): int
    {
        $sorted = $this->sortings()->sum('jumlah');
        $packaged = $this->packagings()->sum('jumlah');
        return max(0, $sorted - $packaged);
    }

    public function getPackagedStockAttribute(): int
    {
        $packaged = $this->packagings()->sum('jumlah');
        $distributed = $this->distribusis()->sum('distribusi_stok_pangan.jumlah');

        return max(0, $packaged - $distributed);
    }

    public function getDistributedStockAttribute(): int
    {
        return max(0, $this->distribusis()->sum('distribusi_stok_pangan.jumlah'));
    }
}
