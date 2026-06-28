<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\SimulasiGudang;
use App\Services\RouteOptimizerService;
use Filament\Notifications\Notification;

class RouteOptimizer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Route Optimizer';

    protected static ?string $title = 'Distribusi Route Optimizer';

    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.route-optimizer';

    // Page state
    public $warehouseLat = -6.91750000; // Bandung city center
    public $warehouseLng = 107.61910000;
    public $isOptimal = false;
    public $latestSimulation = null;
    public $optimizedRoute = [];
    public $routeGenerated = false;

    public function mount()
    {
        // Fetch latest warehouse simulation status
        $this->latestSimulation = SimulasiGudang::orderBy('created_at', 'desc')->first();
        
        if ($this->latestSimulation && $this->latestSimulation->status_bottleneck === 'Optimal') {
            $this->isOptimal = true;
        } else {
            $this->isOptimal = false;
        }
    }

    public function generateRoute(RouteOptimizerService $routeOptimizerService)
    {
        // Re-check constraint
        $this->latestSimulation = SimulasiGudang::orderBy('created_at', 'desc')->first();
        if (!$routeOptimizerService->canGenerateRoute()) {
            $this->isOptimal = false;
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Status bottleneck gudang saat ini tidak Optimal. Selesaikan bottleneck sebelum memulai distribusi!')
                ->danger()
                ->send();
            return;
        }

        $route = $routeOptimizerService->generate((float) $this->warehouseLat, (float) $this->warehouseLng);

        if ($route->isEmpty()) {
            Notification::make()
                ->title('Data Kosong')
                ->body('Belum ada data penerima bantuan untuk dioptimalkan.')
                ->warning()
                ->send();
            return;
        }

        $this->optimizedRoute = $route->toArray();
        $this->routeGenerated = true;

        Notification::make()
            ->title('Rute Optimal Berhasil Dibuat!')
            ->body('Rute terpilih diurutkan berdasarkan jarak terdekat.')
            ->success()
            ->send();
    }

}
