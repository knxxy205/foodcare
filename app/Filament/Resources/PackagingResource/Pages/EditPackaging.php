<?php

namespace App\Filament\Resources\PackagingResource\Pages;

use App\Filament\Resources\PackagingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackaging extends EditRecord
{
    protected static string $resource = PackagingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
