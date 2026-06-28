<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\StokPangan;

class FEFOManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'FEFO Management';

    protected static ?string $title = 'FEFO Management';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.f-e-f-o-management';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function getViewData(): array
    {
        // Order by expiration date ASC (strict FEFO)
        $stocks = StokPangan::orderBy('tanggal_kadaluarsa', 'asc')->get();
        return [
            'stocks' => $stocks,
        ];
    }
}
