<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Distribusi;

class DistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Distribusi Paket Pangan (7 Hari Terakhir)';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        // Calculate sum of distributed packages for last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $sum = Distribusi::where('status', 'selesai')
                ->whereDate('tanggal', $date->toDateString())
                ->sum('jumlah_paket');
                
            $data[] = intval($sum);
            $labels[] = $date->format('d M');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Paket Tersebar',
                    'data' => $data,
                    'borderColor' => '#06B6D4', // cyan-500
                    'backgroundColor' => 'rgba(6, 182, 212, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
