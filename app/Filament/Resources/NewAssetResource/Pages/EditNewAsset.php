<?php

namespace App\Filament\Resources\NewAssetResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\NewAssetResource;

class EditNewAsset extends EditRecord
{
    protected static string $resource = NewAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-s-arrow-left') // Ikon panah ke kiri (opsional)
                ->url($this->getResource()::getUrl('index')) // Kembali ke halaman index
                ->color('primary'), // Warna tombol
        ];
    }
}
