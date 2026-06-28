<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Sorting;
use App\Models\Packaging;
use App\Models\Distribusi;

class VolunteerStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'relawan';
    }

    protected function getStats(): array
    {
        $userId = auth()->id();

        // Tasks today (Pending/In Progress)
        $tugasHariIni = Distribusi::where('relawan_id', $userId)
            ->whereIn('status', ['pending', 'dalam_proses', 'dikirim'])
            ->count();

        // Items sorted
        $totalDisortir = Sorting::where('relawan_id', $userId)->sum('jumlah');

        // Packages wrapped
        $totalDikemas = Packaging::where('relawan_id', $userId)->sum('jumlah');

        // Active delivery
        $distribusiAktif = Distribusi::where('relawan_id', $userId)
            ->whereIn('status', ['dalam_proses', 'dikirim'])
            ->count();

        return [
            Stat::make('Tugas Hari Ini', $tugasHariIni . ' Pengiriman')
                ->description('Memerlukan penanganan Anda')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
            Stat::make('Barang Disortir', number_format($totalDisortir) . ' Unit')
                ->description('Total kontribusi sortasi Anda')
                ->descriptionIcon('heroicon-m-funnel')
                ->color('info'),
            Stat::make('Paket Dikemas', number_format($totalDikemas) . ' Paket')
                ->description('Total kontribusi pengepakan Anda')
                ->descriptionIcon('heroicon-m-gift')
                ->color('success'),
            Stat::make('Distribusi Aktif', $distribusiAktif . ' Lokasi')
                ->description('Dalam proses pengiriman')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),
        ];
    }
}
