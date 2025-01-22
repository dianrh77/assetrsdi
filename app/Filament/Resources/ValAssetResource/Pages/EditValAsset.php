<?php

namespace App\Filament\Resources\ValAssetResource\Pages;

use App\Filament\Resources\ValAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValAsset extends EditRecord
{
    protected static string $resource = ValAssetResource::class;

    protected static ?string $title = 'Validasi Aset';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
