<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianPanganResource\Pages;
use App\Models\PembelianPangan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembelianPanganResource extends Resource
{
    protected static ?string $model = PembelianPangan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Pembelian Pangan';

    protected static ?string $modelLabel = 'Pembelian Pangan';

    protected static ?string $pluralModelLabel = 'Pembelian Pangan';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah (Qnty)')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                $harga = floatval($get('harga_satuan') ?? 0);
                                $set('total_harga', intval($state ?? 0) * $harga);
                            }),
                        Forms\Components\TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->prefix('Rp')
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                $jumlah = intval($get('jumlah') ?? 0);
                                $set('total_harga', $jumlah * floatval($state ?? 0));
                            }),
                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->numeric()
                            ->readOnly()
                            ->default(0)
                            ->prefix('Rp')
                            ->reactive()
                            ->helperText('Total Harga dihitung otomatis dari Jumlah × Harga Satuan.'),
                        Forms\Components\DatePicker::make('tanggal_beli')
                            ->label('Tanggal Pembelian')
                            ->default(now())
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_kadaluarsa')
                            ->label('Tanggal Kadaluarsa')
                            ->default(fn (Forms\Get $get) => Carbon::parse($get('tanggal_beli') ?? now())->addMonths(3)->toDateString())
                            ->afterOrEqual('tanggal_beli')
                            ->helperText('Jika tidak diubah, sistem akan mengatur kadaluarsa 3 bulan sejak pembelian.')
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Belanja')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_beli')
                    ->label('Tanggal Beli')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kadaluarsa')
                    ->label('Tanggal Kadaluarsa')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianPangans::route('/'),
            'create' => Pages\CreatePembelianPangan::route('/create'),
            'edit' => Pages\EditPembelianPangan::route('/{record}/edit'),
        ];
    }
}
