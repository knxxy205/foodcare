<?php

namespace App\Filament\Resources\StokPanganResource\Pages;

use App\Filament\Resources\StokPanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStokPangan extends EditRecord
{
    protected static string $resource = StokPanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
