<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenerimaBantuanResource\Pages;
use App\Models\PenerimaBantuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PenerimaBantuanResource extends Resource
{
    protected static ?string $model = PenerimaBantuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Penerima Bantuan';

    protected static ?string $modelLabel = 'Penerima Bantuan';

    protected static ?string $pluralModelLabel = 'Penerima Bantuan';

    protected static ?int $navigationSort = 8;

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
                        Forms\Components\TextInput::make('nama_penerima')
                            ->label('Nama Penerima / Lembaga')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('kontak')
                            ->label('Nomor Kontak / Telepon')
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->required()
                            ->placeholder('-6.9175'),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->required()
                            ->placeholder('107.6191'),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_penerima')
                    ->label('Nama / Lembaga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50),
                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Latitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Longitude')
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
            'index' => Pages\ListPenerimaBantuans::route('/'),
            'create' => Pages\CreatePenerimaBantuan::route('/create'),
            'edit' => Pages\EditPenerimaBantuan::route('/{record}/edit'),
        ];
    }
}
