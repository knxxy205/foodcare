<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StokPangan;
use App\Services\InventoryService;
use Carbon\Carbon;

class PembelianPangan extends Model
{
    use HasFactory;

    protected $table = 'pembelian_pangan';

    protected $fillable = [
        'nama_barang',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'tanggal_beli',
        'tanggal_kadaluarsa',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($pembelian) {
            if (!$pembelian->tanggal_kadaluarsa) {
                $pembelian->tanggal_kadaluarsa = Carbon::parse($pembelian->tanggal_beli)->addMonths(3)->toDateString();
            }

            if (!$pembelian->total_harga) {
                $pembelian->total_harga = floatval($pembelian->jumlah) * floatval($pembelian->harga_satuan);
            }
        });

        static::created(function ($pembelian) {
            // Automatically insert into stock when a purchase is made
            $stock = StokPangan::create([
                'nama_barang' => $pembelian->nama_barang,
                'jumlah' => $pembelian->jumlah,
                'jumlah_awal' => $pembelian->jumlah,
                'jumlah_tersedia' => $pembelian->jumlah,
                'tanggal_masuk' => $pembelian->tanggal_beli,
                'tanggal_kadaluarsa' => $pembelian->tanggal_kadaluarsa,
            ]);

            app(InventoryService::class)->log(
                $stock,
                'masuk',
                (int) $pembelian->jumlah,
                0,
                (int) $pembelian->jumlah,
                $pembelian,
                'Stok otomatis dari pembelian pangan #' . $pembelian->id
            );
        });
    }
}
