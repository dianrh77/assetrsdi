<?php

namespace App\Filament\Resources\KelolaStokAsetResource\Pages;

use App\Filament\Resources\KelolaStokAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelolaStokAsets extends ListRecords
{
    protected static string $resource = KelolaStokAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
