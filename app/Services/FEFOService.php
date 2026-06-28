<?php

namespace App\Services;

use App\Models\Distribusi;
use App\Models\StokPangan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FEFOService
{
    public function __construct(private readonly InventoryService $inventoryService)
    {
    }

    /**
     * Allocate inventory by strict First Expired First Out and reduce only jumlah_tersedia.
     *
     * @return Collection<int, array{stok_pangan_id:int,nama_barang:string,jumlah:int,tanggal_kadaluarsa:string}>
     */
    public function allocate(Distribusi $distribution, ?string $foodName = null, ?int $quantity = null): Collection
    {
        return DB::transaction(function () use ($distribution, $foodName, $quantity) {
            $requiredQuantity = $quantity ?? (int) $distribution->jumlah_paket;

            $query = StokPangan::query()
                ->where('jumlah_tersedia', '>', 0)
                ->orderBy('tanggal_kadaluarsa')
                ->orderBy('tanggal_masuk')
                ->orderBy('id')
                ->lockForUpdate();

            if ($foodName !== null) {
                $query->where('nama_barang', $foodName);
            }

            $stocks = $query->get()
                ->filter(fn (StokPangan $stock) => $stock->packaged_stock > 0)
                ->values();

            $available = (int) $stocks->sum(fn (StokPangan $stock) => $stock->packaged_stock);

            if ($available < $requiredQuantity) {
                throw new \RuntimeException("Stok siap salur tidak cukup untuk alokasi FEFO. Dibutuhkan {$requiredQuantity}, tersedia {$available}.");
            }

            $remaining = $requiredQuantity;
            $allocations = collect();

            foreach ($stocks as $stock) {
                if ($remaining <= 0) {
                    break;
                }

                $allocated = min($remaining, (int) $stock->packaged_stock, (int) $stock->jumlah_tersedia);

                $distribution->stokPangans()->attach($stock->id, [
                    'nama_barang' => $stock->nama_barang,
                    'jumlah' => $allocated,
                    'tanggal_kadaluarsa' => $stock->tanggal_kadaluarsa,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->inventoryService->reduceAvailableStock(
                    $stock,
                    $allocated,
                    $distribution,
                    'alokasi_fefo',
                    'Alokasi distribusi #' . $distribution->id
                );

                $allocations->push([
                    'stok_pangan_id' => $stock->id,
                    'nama_barang' => $stock->nama_barang,
                    'jumlah' => $allocated,
                    'tanggal_kadaluarsa' => $stock->tanggal_kadaluarsa->toDateString(),
                ]);

                $remaining -= $allocated;
            }

            return $allocations;
        });
    }
}
