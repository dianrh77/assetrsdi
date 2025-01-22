<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\NewAsset;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Enums\ActionsPosition;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Filament\Resources\ValAssetResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use App\Filament\Resources\ValAssetResource\RelationManagers\ValidasiAssetRelationManager;

class ValAssetResource extends Resource
{
    protected static ?string $model = NewAsset::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Validasi Master Aset';

    protected static ?string $navigationGroup = 'Pengelolaan Data Aset';

    protected static ?string $title = 'Validasi Master Aset';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('1. Informasi Umum')
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('nama_alat')
                            ->label('Nama Alat')
                            ->required()
                            ->readOnly()
                            ->maxLength(510),
                        Forms\Components\TextInput::make('merk')
                            ->required()
                            ->readOnly()
                            ->maxLength(510),
                        Forms\Components\TextInput::make('tipe')
                            ->required()
                            ->readOnly()
                            ->maxLength(510),
                        Forms\Components\TextInput::make('no_seri')
                            ->label('No Seri')
                            ->required()
                            ->readOnly()
                            ->maxLength(510),
                    ]),

                Section::make('2. Detail Pembelian')
                    ->columns([
                        'sm' => 3,
                        'xl' => 3,
                    ])
                    ->schema([
                        Forms\Components\DateTimePicker::make('tanggal_invoice')
                            ->label('Tanggal Invoice')
                            ->required()
                            ->readOnly(),
                        Forms\Components\TextInput::make('tahun')
                            ->required()
                            ->readOnly()
                            ->maxLength(510),
                        Forms\Components\TextInput::make('kategori')
                            ->required()
                            ->readOnly(),
                        Forms\Components\TextInput::make('nama_vendor')
                            ->label('Nama Vendor')
                            ->readOnly(),
                    ]),

                Section::make('3. Kalibrasi')
                    ->columns([
                        'sm' => 3,
                        'xl' => 3,
                    ])
                    ->schema([
                        Forms\Components\Radio::make('perlu_kalibrasi')
                            ->label('Perlu Kalibrasi')
                            ->options([
                                false => 'Tidak',
                                true => 'Ya',
                            ])
                            ->inline()
                            ->inlineLabel(false)
                            ->default(false),
                        Forms\Components\DateTimePicker::make('tanggal_kalibrasi')
                            ->label('Tanggal Kalibrasi')
                            ->readOnly(),
                    ]),

                Section::make('4. Informasi Detail Alat')
                    ->columns([
                        'sm' => 3,
                        'xl' => 3,
                    ])
                    ->schema([
                        Forms\Components\DateTimePicker::make('tanggal_penerimaan')
                            ->label('Tanggal Penerimaan')
                            ->required()
                            ->readOnly(),
                        Forms\Components\Radio::make('is_aset')
                            ->label('Aset ?')
                            ->options([
                                false => 'Tidak',
                                true => 'Ya',
                            ])
                            ->inline()
                            ->inlineLabel(false)
                            ->default(true),
                        Forms\Components\TextInput::make('lokasi_alat')
                            ->label('Lokasi Alat')
                            ->required()
                            ->readOnly(),
                        Forms\Components\TextInput::make('jumlah')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                        Forms\Components\TextInput::make('harga')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                        Forms\Components\TextInput::make('no_invent')
                            ->label('No Inventaris')
                            ->required()
                            ->maxLength(510)
                            ->readOnly(),
                        Forms\Components\TextInput::make('kondisi')
                            ->required()
                            ->readOnly(),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_alat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_seri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_invoice')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('nama_vendor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_alat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_invent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kondisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options(NewAsset::query()->pluck('kategori', 'kategori')->toArray())
                    ->label('Kategori'),
                Tables\Filters\SelectFilter::make('lokasi_alat')
                    ->options(NewAsset::query()->pluck('lokasi_alat', 'lokasi_alat')->toArray())
                    ->label('Lokasi Alat'),
            ])
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->actionsColumnLabel('Aksi')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Validasi'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->paginated([10, 25, 50])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable()
                        ->askForFilename()
                        ->withFilename(fn($filename) => 'VALIDASIASSET-' . $filename)
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ValidasiAssetRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListValAssets::route('/'),
            'create' => Pages\CreateValAsset::route('/create'),
            'view' => Pages\ViewValAsset::route('/{record}'),
            'edit' => Pages\EditValAsset::route('/{record}/edit'),
        ];
    }
}
