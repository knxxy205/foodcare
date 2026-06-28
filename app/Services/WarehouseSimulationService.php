<?php

namespace App\Services;

use App\Models\SimulasiGudang;

class WarehouseSimulationService
{
    public function simulate(int $waitingItems, int $volunteerCount, int $sortingCapacity, int $packagingCapacity): SimulasiGudang
    {
        $totalSortingCapacity = $volunteerCount * $sortingCapacity;
        $totalPackagingCapacity = $volunteerCount * $packagingCapacity;

        $status = $waitingItems <= min($totalSortingCapacity, $totalPackagingCapacity) ? 'Optimal' : 'Bottleneck';

        return SimulasiGudang::create([
            'jumlah_barang' => $waitingItems,
            'jumlah_relawan' => $volunteerCount,
            'kapasitas_sorting' => $totalSortingCapacity,
            'kapasitas_packaging' => $totalPackagingCapacity,
            'status_bottleneck' => $status,
        ]);
    }
}

