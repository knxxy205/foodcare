<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonasiResource\Pages;
use App\Models\Donasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DonasiResource extends Resource
{
    protected static ?string $model = Donasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Donasi Masuk';

    protected static ?string $modelLabel = 'Donasi Masuk';

    protected static ?string $pluralModelLabel = 'Donasi Masuk';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Donatur')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('program_id')
                            ->relationship('program', 'nama_program')
                            ->label('Program Donasi')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah Donasi')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('status')
                            ->label('Status Verifikasi')
                            ->options([
                                'pending' => 'Pending',
                                'success' => 'Success',
                                'failed' => 'Failed',
                            ])
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('order_id')
                            ->label('Order ID Midtrans')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('payment_type')
                            ->label('Tipe Pembayaran')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('midtrans_status')
                            ->label('Status Midtrans')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('tanggal')
                            ->label('Tanggal Donasi')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Tanggal Bayar')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Donatur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program.nama_program')
                    ->label('Program Donasi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode'),
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Dibayar')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Disable bulk deletes to prevent data tampering
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonasis::route('/'),
            'view' => Pages\ViewDonasi::route('/{record}'),
        ];
    }
}
