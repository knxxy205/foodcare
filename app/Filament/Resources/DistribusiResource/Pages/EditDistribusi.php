<?php

namespace App\Filament\Resources\DistribusiResource\Pages;

use App\Filament\Resources\DistribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistribusi extends EditRecord
{
    protected static string $resource = DistribusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
