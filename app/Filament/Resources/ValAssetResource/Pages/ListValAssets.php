<?php

namespace App\Filament\Resources\ValAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ValAssetResource;

class ListValAssets extends ListRecords
{
    protected static string $resource = ValAssetResource::class;

    protected ?string $heading = 'Validasi Aset';

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Belum Validasi' => Tab::make()
                ->query(fn($query) => $query->whereDoesntHave('validasiAsset')),
            'Sudah Validasi' => Tab::make()
                ->query(fn($query) => $query->whereHas('validasiAsset')),
            'Semua' => Tab::make(),
        ];
    }
}
