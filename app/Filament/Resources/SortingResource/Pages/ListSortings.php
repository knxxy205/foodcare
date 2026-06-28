<?php

namespace App\Filament\Resources\SortingResource\Pages;

use App\Filament\Resources\SortingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSortings extends ListRecords
{
    protected static string $resource = SortingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
