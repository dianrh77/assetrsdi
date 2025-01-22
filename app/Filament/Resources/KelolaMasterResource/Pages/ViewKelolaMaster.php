<?php

namespace App\Filament\Resources\KelolaMasterResource\Pages;

use App\Filament\Resources\KelolaMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKelolaMaster extends ViewRecord
{
    protected static string $resource = KelolaMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
