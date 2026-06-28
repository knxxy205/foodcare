<?php

namespace App\Filament\Resources\PembelianPanganResource\Pages;

use App\Filament\Resources\PembelianPanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelianPangan extends EditRecord
{
    protected static string $resource = PembelianPanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
