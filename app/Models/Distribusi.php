<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SimulasiGudang;
use App\Services\FEFOService;

class Distribusi extends Model
{
    use HasFactory;

    protected $table = 'distribusi';

    protected $fillable = [
        'relawan_id',
        'penerima_id',
        'nama_barang',
        'jumlah_paket',
        'status',
        'tanggal',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function relawan()
    {
        return $this->belongsTo(User::class, 'relawan_id');
    }

    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'penerima_id');
    }

    public function stokPangans()
    {
        return $this->belongsToMany(StokPangan::class, 'distribusi_stok_pangan', 'distribusi_id', 'stok_pangan_id')
                    ->withPivot('jumlah', 'nama_barang', 'tanggal_kadaluarsa')
                    ->withTimestamps();
    }

    protected static function booted()
    {
        static::creating(function ($distribusi) {
            $latestStatus = SimulasiGudang::latest()->value('status_bottleneck');
            if ($latestStatus !== 'Optimal') {
                throw new \RuntimeException('Distribusi hanya dapat dibuat saat status gudang Optimal.');
            }
        });

        static::created(function ($distribusi) {
            app(FEFOService::class)->allocate($distribusi, $distribusi->nama_barang);
        });
    }
}
