<?php

namespace App\Services;

use App\Models\RiwayatStokPangan;
use App\Models\StokPangan;
use Illuminate\Database\Eloquent\Model;

class InventoryService
{
    public function createStock(array $data, ?Model $reference = null, ?string $note = null): StokPangan
    {
        $initialQuantity = (int) ($data['jumlah_awal'] ?? $data['jumlah'] ?? 0);

        $stock = StokPangan::create([
            'nama_barang' => $data['nama_barang'],
            'jumlah' => $initialQuantity,
            'jumlah_awal' => $initialQuantity,
            'jumlah_tersedia' => (int) ($data['jumlah_tersedia'] ?? $initialQuantity),
            'tanggal_masuk' => $data['tanggal_masuk'],
            'tanggal_kadaluarsa' => $data['tanggal_kadaluarsa'],
        ]);

        $this->log($stock, 'masuk', $stock->jumlah_tersedia, 0, $stock->jumlah_tersedia, $reference, $note);

        return $stock;
    }

    public function reduceAvailableStock(StokPangan $stock, int $quantity, Model $reference, string $type = 'alokasi_fefo', ?string $note = null): void
    {
        $before = (int) $stock->jumlah_tersedia;
        $after = $before - $quantity;

        if ($quantity <= 0 || $after < 0) {
            throw new \InvalidArgumentException('Jumlah alokasi stok tidak valid.');
        }

        $stock->forceFill(['jumlah_tersedia' => $after])->save();

        $this->log($stock, $type, -$quantity, $before, $after, $reference, $note);
    }

    public function log(StokPangan $stock, string $type, int $change, int $before, int $after, ?Model $reference = null, ?string $note = null): RiwayatStokPangan
    {
        return RiwayatStokPangan::create([
            'stok_pangan_id' => $stock->id,
            'reference_type' => $reference?->getMorphClass(),
            'reference_id' => $reference?->getKey(),
            'tipe' => $type,
            'jumlah_perubahan' => $change,
            'jumlah_sebelum' => $before,
            'jumlah_sesudah' => $after,
            'catatan' => $note,
        ]);
    }
}

