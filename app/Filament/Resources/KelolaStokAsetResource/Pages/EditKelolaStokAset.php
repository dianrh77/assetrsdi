<?php

namespace App\Filament\Resources\KelolaStokAsetResource\Pages;

use App\Filament\Resources\KelolaStokAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelolaStokAset extends EditRecord
{
    protected static string $resource = KelolaStokAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
