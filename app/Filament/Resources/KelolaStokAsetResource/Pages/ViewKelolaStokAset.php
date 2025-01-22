<?php

namespace App\Filament\Resources\KelolaStokAsetResource\Pages;

use App\Filament\Resources\KelolaStokAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKelolaStokAset extends ViewRecord
{
    protected static string $resource = KelolaStokAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
