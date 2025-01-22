<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Asset;
use Filament\Forms\Form;
use Illuminate\View\View;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\AssetResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Data Aset';

    protected static ?string $title = 'Data Aset';

    protected static ?int $navigationSort = 1;

    public function getHeader(): ?View
    {
        return view('filament.settings.custom-header');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_alat')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('merk')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('tipe')
                    ->maxLength(510),
                Forms\Components\TextInput::make('no_seri')
                    ->required()
                    ->maxLength(510),
                Forms\Components\DateTimePicker::make('tanggal_invoice')
                    ->required(),
                Forms\Components\TextInput::make('tahun')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('nama_vendor')
                    ->required()
                    ->maxLength(510),
                Forms\Components\Toggle::make('perlu_kalibrasi')
                    ->required(),
                Forms\Components\DateTimePicker::make('tanggal_kalibrasi'),
                Forms\Components\DateTimePicker::make('tanggal_penerimaan')
                    ->required(),
                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->maxLength(510),
                Forms\Components\Toggle::make('is_aset')
                    ->required(),
                Forms\Components\TextInput::make('lokasi_alat')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('no_invent')
                    ->required()
                    ->maxLength(510),
                Forms\Components\TextInput::make('kondisi')
                    ->required()
                    ->maxLength(510),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('kondisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_invent')
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('kategori')
                    ->options(Asset::query()->pluck('kategori', 'kategori')->toArray())
                    ->label('Kategori'),
                Tables\Filters\SelectFilter::make('lokasi_alat')
                    ->options(Asset::query()->pluck('lokasi_alat', 'lokasi_alat')->toArray())
                    ->label('Lokasi Alat'),
            ])
            ->deferLoading()
            ->defaultPaginationPageOption(10)
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),

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
                        ->withFilename(fn($filename) => 'ASSETRSDI-' . $filename)
                ])
            ]);
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'view' => Pages\ViewAsset::route('/{record}'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
