<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\StokPangan;
use App\Models\SimulasiGudang as SimulasiModel;
use App\Services\WarehouseSimulationService;
use Filament\Notifications\Notification;

class SimulasiGudang extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Simulasi Gudang';

    protected static ?string $title = 'Simulasi Kemacetan Gudang';

    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.pages.simulasi-gudang';

    // Form states
    public ?int $jumlah_barang = 0;
    public ?int $jumlah_relawan = 0;
    public ?int $sorting_rate = 10; // items per volunteer per hour
    public ?int $packaging_rate = 8; // items per volunteer per hour

    // Results states
    public $lastSimulation = null;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function mount()
    {
        // 1. Calculate live queue: sum of raw stock waiting for sorting
        $stocks = StokPangan::all();
        $liveQueue = 0;
        foreach ($stocks as $stock) {
            $liveQueue += $stock->raw_stock;
        }

        // 2. Count live active volunteers
        $liveVolunteers = User::where('role', 'relawan')->count();

        // 3. Prefill forms
        $this->jumlah_barang = $liveQueue;
        $this->jumlah_relawan = $liveVolunteers > 0 ? $liveVolunteers : 5; // Default fallback to 5

        // Fetch last simulation run
        $this->lastSimulation = SimulasiModel::orderBy('created_at', 'desc')->first();
    }

    public function runSimulation(WarehouseSimulationService $simulationService)
    {
        $this->validate([
            'jumlah_barang' => 'required|integer|min:0',
            'jumlah_relawan' => 'required|integer|min:1',
            'sorting_rate' => 'required|integer|min:1',
            'packaging_rate' => 'required|integer|min:1',
        ]);

        $this->lastSimulation = $simulationService->simulate(
            (int) $this->jumlah_barang,
            (int) $this->jumlah_relawan,
            (int) $this->sorting_rate,
            (int) $this->packaging_rate
        );

        Notification::make()
            ->title('Simulasi Berhasil Dijalankan!')
            ->body('Status Gudang: ' . $this->lastSimulation->status_bottleneck)
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $history = SimulasiModel::orderBy('created_at', 'desc')->take(10)->get();
        return [
            'history' => $history,
        ];
    }
}
