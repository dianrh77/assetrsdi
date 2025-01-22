<?php

namespace App\Filament\Resources\KelolaStokAsetResource\RelationManagers;

use App\Models\KelolaStokAset;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class StokAsetRelationManager extends RelationManager
{
    protected static string $relationship = 'stokAset';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_asset')
                    ->required()
                    ->readOnly()
                    ->afterStateHydrated(function (TextInput $component, ?KelolaStokAset $record, RelationManager $livewire) {
                        $parentRecord = $livewire->ownerRecord;
                        if ($parentRecord) {
                            $component->state(trim($parentRecord->id)); // Mengisi field kd_rekmed
                        }
                    }),
                Forms\Components\DateTimePicker::make('tanggal_adjust')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('tipe')
                    ->required()
                    ->options([
                        'Tambah' => 'Tambah',
                        'Kurang' => 'Kurang',
                    ]),
                Forms\Components\TextInput::make('qty')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('AdjustStok')
            ->columns([
                Tables\Columns\TextColumn::make('id_asset'),
                Tables\Columns\TextColumn::make('tanggal_adjust'),
                Tables\Columns\TextColumn::make('qty'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Kelola Stok'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
