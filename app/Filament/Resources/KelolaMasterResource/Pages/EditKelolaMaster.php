<?php

namespace App\Filament\Resources\KelolaMasterResource\Pages;

use App\Filament\Resources\KelolaMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelolaMaster extends EditRecord
{
    protected static string $resource = KelolaMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
