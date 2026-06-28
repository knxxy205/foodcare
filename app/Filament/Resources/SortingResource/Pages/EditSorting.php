<?php

namespace App\Filament\Resources\SortingResource\Pages;

use App\Filament\Resources\SortingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSorting extends EditRecord
{
    protected static string $resource = SortingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
