<?php

namespace App\Filament\Resources\NewAssetResource\Pages;

use App\Filament\Resources\NewAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewAssets extends ListRecords
{
    protected static string $resource = NewAssetResource::class;

    protected ?string $heading = 'Pendataan Aset';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
