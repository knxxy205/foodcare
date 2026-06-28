<?php

namespace App\Filament\Resources\ProgramDonasiResource\Pages;

use App\Filament\Resources\ProgramDonasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgramDonasis extends ListRecords
{
    protected static string $resource = ProgramDonasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
