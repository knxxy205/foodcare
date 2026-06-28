<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Donasi;

class DonationChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Donasi Masuk (7 Hari Terakhir)';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        // Calculate sum of successful donations for last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $sum = Donasi::where('status', 'success')
                ->whereDate('tanggal', $date->toDateString())
                ->sum('jumlah');
                
            $data[] = floatval($sum);
            $labels[] = $date->format('d M');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Donasi (Rp)',
                    'data' => $data,
                    'borderColor' => '#10B981', // emerald-500
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
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
