<?php

namespace App\Filament\Resources\AssetResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AssetResource;

class ViewAsset extends ViewRecord
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-s-arrow-left') // Ikon panah ke kiri (opsional)
                ->url($this->getResource()::getUrl('index')) // Kembali ke halaman index
                ->color('primary'), // Warna tombol
        ];
    }
}
