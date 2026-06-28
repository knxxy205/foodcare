<?php

namespace App\Filament\Resources\PembelianPanganResource\Pages;

use App\Filament\Resources\PembelianPanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembelianPangans extends ListRecords
{
    protected static string $resource = PembelianPanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
