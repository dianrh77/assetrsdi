<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Vendor Baru';

    protected static ?string $navigationGroup = 'Pengelolaan Data Aset';

    protected static ?string $title = 'Vendor Baru';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kd_perk')
                    ->label('Kode Perkiraan')
                    ->maxLength(20),
                Forms\Components\TextInput::make('nm_perk')
                    ->label('Nama Vendor')
                    ->maxLength(100),
                Forms\Components\TextInput::make('kelompok')
                    ->maxLength(100),
                // Forms\Components\TextInput::make('kd_perk_lama')
                //     ->maxLength(20),
                // Forms\Components\TextInput::make('nm_perk_lama')
                //     ->maxLength(100),
                // Forms\Components\TextInput::make('sub_kd_perk_lama')
                //     ->maxLength(20),
                // Forms\Components\TextInput::make('sub_nm_perk_lama')
                //     ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('kelompok')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('kd_perk')
                    ->label('Kode Perkiraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nm_perk')
                    ->label('Nama Vendor')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('kd_perk_lama')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('nm_perk_lama')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('sub_kd_perk_lama')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('sub_nm_perk_lama')
                //     ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->paginated([10, 25, 50])
            ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('kelompok', ['5', '6'])
                ->whereRaw("LEFT(kd_perk, 5) in ('21103','21105')")
                ->orderBy('kd_perk'));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'view' => Pages\ViewVendor::route('/{record}'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
