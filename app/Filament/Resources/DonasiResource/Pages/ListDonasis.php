<?php

namespace App\Filament\Resources\DonasiResource\Pages;

use App\Filament\Resources\DonasiResource;
use Filament\Resources\Pages\ListRecords;

class ListDonasis extends ListRecords
{
    protected static string $resource = DonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
