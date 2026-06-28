<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokPanganResource\Pages;
use App\Models\StokPangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StokPanganResource extends Resource
{
    protected static ?string $model = StokPangan::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Stok Pangan';

    protected static ?string $modelLabel = 'Stok Pangan';

    protected static ?string $pluralModelLabel = 'Stok Pangan';

    protected static ?int $navigationSort = 4;

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
                            ->maxLength(255),
                        Forms\Components\TextInput::make('jumlah_awal')
                            ->label('Jumlah Awal')
                            ->numeric()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('jumlah_tersedia', (int) $state)),
                        Forms\Components\TextInput::make('jumlah_tersedia')
                            ->label('Jumlah Tersedia')
                            ->numeric()
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_masuk')
                            ->label('Tanggal Masuk')
                            ->default(now())
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_kadaluarsa')
                            ->label('Tanggal Kadaluarsa')
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
                Tables\Columns\TextColumn::make('jumlah_awal')
                    ->label('Stok Masuk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_tersedia')
                    ->label('Tersedia')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('raw_stock')
                    ->label('Belum Disortir')
                    ->state(fn ($record) => $record->raw_stock)
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('sorted_stock')
                    ->label('Belum Dikemas')
                    ->state(fn ($record) => $record->sorted_stock)
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray'),
                Tables\Columns\TextColumn::make('packaged_stock')
                    ->label('Siap Salurkan')
                    ->state(fn ($record) => $record->packaged_stock)
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kadaluarsa')
                    ->label('Tanggal Kadaluarsa')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                // Filter by expiration status
                Tables\Filters\Filter::make('expired_soon')
                    ->label('Segera Kadaluarsa (30 hari)')
                    ->query(fn ($query) => $query->where('tanggal_kadaluarsa', '<=', now()->addDays(30))),
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
            'index' => Pages\ListStokPangans::route('/'),
            'create' => Pages\CreateStokPangan::route('/create'),
            'edit' => Pages\EditStokPangan::route('/{record}/edit'),
        ];
    }
}
