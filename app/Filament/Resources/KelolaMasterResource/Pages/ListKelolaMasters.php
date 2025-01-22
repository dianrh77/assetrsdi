<?php

namespace App\Filament\Resources\KelolaMasterResource\Pages;

use App\Filament\Resources\KelolaMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelolaMasters extends ListRecords
{
    protected static string $resource = KelolaMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
