<?php

namespace App\Filament\Resources\NewAssetResource\Pages;

use App\Filament\Resources\NewAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewAsset extends CreateRecord
{
    protected static string $resource = NewAssetResource::class;

    protected ?string $heading = 'Pendataan Aset Baru';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-s-arrow-left') // Ikon panah ke kiri (opsional)
                ->url($this->getResource()::getUrl('index')) // Kembali ke halaman index
                ->color('primary'), // Warna tombol
        ];
    }
}
