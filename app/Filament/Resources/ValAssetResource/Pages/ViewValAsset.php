<?php

namespace App\Filament\Resources\ValAssetResource\Pages;

use App\Filament\Resources\ValAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewValAsset extends ViewRecord
{
    protected static string $resource = ValAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
