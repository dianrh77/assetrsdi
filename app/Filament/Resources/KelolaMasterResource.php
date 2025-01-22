<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\KelolaMaster;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KelolaMasterResource\Pages;
use App\Filament\Resources\KelolaMasterResource\RelationManagers;

class KelolaMasterResource extends Resource
{
    protected static ?string $model = KelolaMaster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelola Master Aset';

    protected static ?string $navigationGroup = 'Pengelolaan Data Aset';

    protected static ?string $title = 'Kelola Master Aset';

    protected static ?int $navigationSort = 4;

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
                            ->maxLength(510),
                        Forms\Components\TextInput::make('merk')
                            ->required()
                            ->maxLength(510),
                        Forms\Components\TextInput::make('tipe')
                            ->required()
                            ->maxLength(510),
                        Forms\Components\TextInput::make('no_seri')
                            ->label('No Seri')
                            ->required()
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
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // Ambil tahun dari tanggal yang dipilih
                                    $tahun = \Carbon\Carbon::parse($state)->format('Y');
                                    $set('tahun', $tahun); // Set nilai 'tahun' secara otomatis
                                }
                            }),
                        Forms\Components\TextInput::make('tahun')
                            ->required()
                            ->readOnly()
                            ->maxLength(510),
                        Forms\Components\Select::make('kategori')
                            ->required()
                            ->label('Kategori')
                            ->options([
                                'Medis' => 'Medis',
                                'Non Medis' => 'Non Medis',
                            ])
                            ->reactive(), // Menjadikan kategori sebagai field reactive
                        Forms\Components\Select::make('nama_vendor')
                            ->label('Nama Vendor')
                            ->required()
                            ->searchable()
                            ->options(function (callable $get) {
                                // Ambil nilai dari kategori
                                $kategori = $get('kategori');

                                // Tentukan query berdasarkan kategori
                                $query = \App\Models\Vendor::query();

                                if ($kategori === 'Medis') {
                                    $query->whereRaw("LEFT(kd_perk, 5) = '21103'");
                                } elseif ($kategori === 'Non Medis') {
                                    $query->whereRaw("LEFT(kd_perk, 5) = '21105'");
                                }

                                // Cek apakah ada query pencarian (nilai input pengguna)
                                $searchTerm = request()->input('q'); // Ambil kata pencarian
                                if ($searchTerm) {
                                    $query->where('nm_perk', 'LIKE', '%' . $searchTerm . '%'); // Hanya tampilkan vendor yang sesuai dengan pencarian
                                }

                                // Ambil hasil query
                                $options = $query
                                    ->whereIn('kelompok', ['5', '6'])
                                    ->orderBy('perkiraan_new.nm_perk')
                                    ->pluck('perkiraan_new.nm_perk', 'perkiraan_new.nm_perk')
                                    ->toArray(); // Ubah ke array agar bisa dimanipulasi

                                // Tambahkan pilihan "Tidak Diketahui"
                                return ['Tidak Diketahui' => 'Tidak Diketahui'] + $options; // Prepend opsi
                            })
                            ->placeholder('Pilih Vendor')
                            ->preload(),
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
                            ->default(false)
                            ->reactive() // Membuat komponen reaktif terhadap perubahan
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Mengubah visibilitas komponen tanggal_kalibrasi berdasarkan pilihan
                                $set('tanggal_kalibrasi', $state ? null : null); // Mengatur nilai ke null
                            }),
                        Forms\Components\DateTimePicker::make('tanggal_kalibrasi')
                            ->label('Tanggal Kalibrasi')
                            ->visible(fn($get) => (bool) $get('perlu_kalibrasi') === true)
                            ->required(fn($get) => (bool) $get('perlu_kalibrasi') === true),
                    ]),

                Section::make('4. Informasi Detail Alat')
                    ->columns([
                        'sm' => 3,
                        'xl' => 3,
                    ])
                    ->schema([
                        Forms\Components\DateTimePicker::make('tanggal_penerimaan')
                            ->label('Tanggal Penerimaan')
                            ->required(),
                        Forms\Components\Radio::make('is_aset')
                            ->label('Aset ?')
                            ->options([
                                false => 'Tidak',
                                true => 'Ya',
                            ])
                            ->inline()
                            ->inlineLabel(false)
                            ->default(true),
                        // Forms\Components\TextInput::make('lokasi_alat')
                        //     ->label('Lokasi Alat')
                        //     ->required()
                        //     ->maxLength(510),
                        Forms\Components\Select::make('lokasi_alat')
						->label('Lokasi Alat')
						->required()
						->searchable()
						->options(fn() => \App\Models\Ruang::where('status', true)->pluck('nama_ruang', 'nama_ruang'))
						->placeholder('Pilih Lokasi')
						->preload()
						->createOptionForm([
							Forms\Components\TextInput::make('name')
								->label('Nama Lokasi')
								->required()
								->maxLength(255),
							Forms\Components\TextInput::make('status')
								->label('Status')
								->readOnly()
								->default(true),
						])
						->createOptionUsing(function ($data) {
							\App\Models\Ruang::create([
								'nama_ruang' => $data['name'],
								'status' => $data['status'],
							]);

							return $data['name']; // Kembalikan nama_ruang untuk dijadikan pilihan.
						}),
                        Forms\Components\TextInput::make('harga')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('no_invent')
                            ->label('No Inventaris')
                            ->required()
                            ->maxLength(510),
                        Forms\Components\Select::make('kondisi')
                            ->required()
                            ->label('Kondis Alat')
                            ->options([
                                'Baik' => 'Baik',
                                'Rusak' => 'Rusak',
                            ]),
                    ])

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
                Tables\Columns\TextColumn::make('merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_seri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_invoice')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_vendor')
                    ->searchable(),
                Tables\Columns\IconColumn::make('perlu_kalibrasi')
                    ->boolean(),
                Tables\Columns\TextColumn::make('tanggal_kalibrasi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_penerimaan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_aset')
                    ->boolean(),
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
                //
            ])

            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->actionsColumnLabel('Aksi')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelolaMasters::route('/'),
            'create' => Pages\CreateKelolaMaster::route('/create'),
            'view' => Pages\ViewKelolaMaster::route('/{record}'),
            'edit' => Pages\EditKelolaMaster::route('/{record}/edit'),
        ];
    }
}
