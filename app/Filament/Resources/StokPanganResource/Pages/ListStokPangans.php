<?php

namespace App\Filament\Resources\StokPanganResource\Pages;

use App\Filament\Resources\StokPanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStokPangans extends ListRecords
{
    protected static string $resource = StokPanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
