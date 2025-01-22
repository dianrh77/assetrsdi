<?php

namespace App\Filament\Resources\ValAssetResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\ValAsset;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ValidasiAssetRelationManager extends RelationManager
{
    protected static string $relationship = 'validasiAsset';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_asset')
                    ->label('ID ASSET')
                    ->afterStateHydrated(function (TextInput $component, ?ValAsset $record, RelationManager $livewire) {
                        $parentRecord = $livewire->ownerRecord;
                        if ($parentRecord) {
                            $component->state(trim($parentRecord->id)); // Mengisi field kd_rekmed
                        }
                    })
                    ->readOnly(),
                Forms\Components\TextInput::make('nama_alat')
                    ->label('Nama Alat')
                    ->afterStateHydrated(function (TextInput $component, ?ValAsset $record, RelationManager $livewire) {
                        $parentRecord = $livewire->ownerRecord;
                        if ($parentRecord) {
                            $component->state(trim($parentRecord->nama_alat)); // Mengisi field kd_rekmed
                        }
                    })
                    ->readOnly(),
                Forms\Components\DateTimePicker::make('tanggal_verifikasi')
                    ->required()
                    ->label('Tanggal Verifikasi'),
                Radio::make('berkas_lengkap')
                    ->label('Berkas Lengkap')
                    ->options([
                        false => 'Tidak',
                        true => 'Ya',
                    ])
                    ->inline()
                    ->inlineLabel(false)
                    ->default(true),
                Radio::make('kondisi_asset')
                    ->label('Kondisi Aset')
                    ->options([
                        false => 'Rusak',
                        true => 'Baik',
                    ])
                    ->inline()
                    ->inlineLabel(false)
                    ->default(true),
                Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ValidationAsset')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_verifikasi'),
                Tables\Columns\IconColumn::make('berkas_lengkap')
                    ->label('Berkas Lengkap/Tidak')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                Tables\Columns\IconColumn::make('kondisi_asset')
                    ->label('Kondisi Baik/Tidak')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Validasi')
                    ->after(function ($record) {
                        // Ambil ID aset dari $record
                        $newAsset = \App\Models\NewAsset::find($record->id_asset);

                        if ($newAsset) {
                            // Pindahkan data ke tabel asset (fix_asset)
                            $createdAsset = \App\Models\Asset::create([
                                'nama_alat' => $newAsset->nama_alat,
                                'merk' => $newAsset->merk,
                                'tipe' => $newAsset->tipe,
                                'no_seri' => $newAsset->no_seri,
                                'tanggal_invoice' => $newAsset->tanggal_invoice,
                                'tahun' => $newAsset->tahun,
                                'nama_vendor' => $newAsset->nama_vendor,
                                'perlu_kalibrasi' => $newAsset->perlu_kalibrasi,
                                'tanggal_kalibrasi' => $newAsset->tanggal_kalibrasi,
                                'tanggal_penerimaan' => $newAsset->tanggal_penerimaan,
                                'kategori' => $newAsset->kategori,
                                'is_aset' => $newAsset->is_aset,
                                'lokasi_alat' => $newAsset->lokasi_alat,
                                'jumlah' => $newAsset->jumlah,
                                'harga' => $newAsset->harga,
                                'no_invent' => $newAsset->no_invent,
                                'kondisi' => $newAsset->kondisi,
                                // Tambahkan kolom lain sesuai kebutuhan
                            ]);

                            // Pastikan data berhasil disimpan ke tabel Asset
                            if ($createdAsset) {
                                // Simpan data ke tabel adjust_stook
                                \App\Models\AdjustStok::create([
                                    'id_asset' => $createdAsset->id, // Ambil ID dari tabel Asset yang baru dibuat
                                    'tanggal_adjust' => now(),
                                    'tipe' => 'Tambah',
                                    'qty' => $newAsset->jumlah,      // Gunakan jumlah dari new_asset
                                ]);
                            }
                        }

                        // Redirect setelah create berhasil (opsional)
                        return redirect()->route('filament.admin.resources.val-assets.index'); // Sesuaikan route dengan resource Anda
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
