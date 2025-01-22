<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\KelolaStokAset;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KelolaStokAsetResource\Pages;
use App\Filament\Resources\KelolaStokAsetResource\RelationManagers;
use App\Filament\Resources\KelolaStokAsetResource\RelationManagers\StokAsetRelationManager;
use Filament\Forms\Components\Actions;

class KelolaStokAsetResource extends Resource
{
    protected static ?string $model = KelolaStokAset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelola Stok Aset';

    protected static ?string $navigationGroup = 'Pengelolaan Data Aset';

    protected static ?string $title = 'Kelola Stok Aset';

    protected static ?int $navigationSort = 5;

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
                            ->label('QTY Awal')
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
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Kolom nomor urut
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi_alat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_alat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun'),
                // Tables\Columns\TextColumn::make('jumlah')
                //     ->label('QTY')
                //     ->numeric(),
                Tables\Columns\TextColumn::make('jumlah_terkini')
                ->label('QTY')
                ->getStateUsing(function ($record) {
                    // Cek apakah ada data terkait di tabel_so berdasarkan id_asset, ambil yang terbaru
                    $soAdjustment = $record->tabelSo()
                        ->where('id_master', $record->id)
                        ->latest('CreateDate') // Mengurutkan berdasarkan created_at (terbaru)
                        ->first();

                    if ($soAdjustment) {
                        // Jika ada, tampilkan nilai dari tabel_so (misalnya kolom qty di tabel_so)
                        return $soAdjustment->jumlah; // Ganti dengan kolom yang sesuai dari tabel_so
                    } else {
                        // Jika tidak ada, tampilkan nilai berdasarkan stokAset
                        $adjustmentsTotal = $record->stokAset()->sum('qty'); // Total dari stokAset
                        return $adjustmentsTotal; // Tampilkan total penyesuaian dari stokAset
                    }
                }),
            ])
            ->filters([
                //
            ])
            ->deferLoading()
            ->defaultPaginationPageOption(10)
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('left')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Kelola Stok'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            StokAsetRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelolaStokAsets::route('/'),
            'create' => Pages\CreateKelolaStokAset::route('/create'),
            'view' => Pages\ViewKelolaStokAset::route('/{record}'),
            'edit' => Pages\EditKelolaStokAset::route('/{record}/edit'),
        ];
    }
}
