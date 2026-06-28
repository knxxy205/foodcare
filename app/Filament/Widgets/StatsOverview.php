<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Donasi;
use App\Models\ProgramDonasi;
use App\Models\StokPangan;
use App\Models\Distribusi;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    protected function getStats(): array
    {
        $totalDonasi = Donasi::where('status', 'success')->sum('jumlah');
        $totalProgram = ProgramDonasi::count();
        
        $stocks = StokPangan::all();
        $totalStok = 0;
        foreach ($stocks as $stock) {
            $totalStok += $stock->packaged_stock;
        }

        $totalDistribusi = Distribusi::where('status', 'selesai')->count();

        return [
            Stat::make('Total Donasi Terkumpul', 'Rp ' . number_format($totalDonasi, 0, ',', '.'))
                ->description('Dari semua program bantuan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Total Program Donasi', $totalProgram . ' Program')
                ->description('Aktif dan selesai')
                ->descriptionIcon('heroicon-m-gift')
                ->color('info'),
            Stat::make('Stok Siap Salur', number_format($totalStok) . ' Unit')
                ->description('Telah disortir & dikemas')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),
            Stat::make('Bantuan Tersalurkan', $totalDistribusi . ' Titik')
                ->description('Distribusi selesai')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),
        ];
    }
}
