<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackagingResource\Pages;
use App\Models\Packaging;
use App\Models\StokPangan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PackagingResource extends Resource
{
    protected static ?string $model = Packaging::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift-top';

    protected static ?string $navigationLabel = 'Packaging';

    protected static ?string $modelLabel = 'Packaging';

    protected static ?string $pluralModelLabel = 'Packaging';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('relawan_id')
                            ->label('Relawan')
                            ->options(fn () => User::where('role', 'relawan')->pluck('name', 'id'))
                            ->default(fn () => auth()->user()->role === 'relawan' ? auth()->id() : null)
                            ->disabled(!$isAdmin)
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('stok_id')
                            ->label('Stok Barang (Menunggu Packaging)')
                            ->options(fn () => StokPangan::all()
                                ->filter(fn ($stok) => $stok->sorted_stock > 0 || request()->route('record')) // Allow edit view
                                ->mapWithKeys(fn ($stok) => [$stok->id => $stok->nama_barang . " (Sisa: " . $stok->sorted_stock . ")"])
                            )
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah Dikemas')
                            ->numeric()
                            ->required()
                            ->rules([
                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $stokId = $get('stok_id');
                                    if (!$stokId) {
                                        return;
                                    }
                                    
                                    $stok = StokPangan::find($stokId);
                                    if (!$stok) {
                                        return;
                                    }

                                    // For edit context, we need to add the current record's amount back
                                    $currentRecordId = request()->route('record');
                                    $currentAmount = 0;
                                    if ($currentRecordId) {
                                        $currentPackaging = Packaging::find($currentRecordId);
                                        if ($currentPackaging && $currentPackaging->stok_id == $stokId) {
                                            $currentAmount = $currentPackaging->jumlah;
                                        }
                                    }

                                    $available = $stok->sorted_stock + $currentAmount;
                                    if ($value > $available) {
                                        $fail("Jumlah yang dikemas ({$value}) melebihi stok yang sudah disortir ({$available}).");
                                    }
                                },
                            ]),
                        Forms\Components\DateTimePicker::make('waktu_proses')
                            ->label('Waktu Proses')
                            ->default(now())
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->modifyQueryUsing(function ($query) use ($user) {
                // Relawan can only see their packaging tasks
                if ($user->role === 'relawan') {
                    $query->where('relawan_id', $user->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('relawan.name')
                    ->label('Relawan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: auth()->user()->role === 'relawan'),
                Tables\Columns\TextColumn::make('stok.nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah Dikemas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_alur')
                    ->label('Status Alur')
                    ->state(fn (Packaging $record): string => static::stockFlowStatus($record->stok))
                    ->badge()
                    ->color(fn (Packaging $record): string => static::stockFlowColor($record->stok)),
                Tables\Columns\TextColumn::make('waktu_proses')
                    ->label('Waktu Proses')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Packaging $record): bool => ! static::isStockFullyDistributed($record->stok)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Packaging $record): bool => ! static::isStockFullyDistributed($record->stok)),
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
            'index' => Pages\ListPackagings::route('/'),
            'create' => Pages\CreatePackaging::route('/create'),
            'edit' => Pages\EditPackaging::route('/{record}/edit'),
        ];
    }

    private static function stockFlowStatus(?StokPangan $stock): string
    {
        if (!$stock) {
            return 'Stok tidak ditemukan';
        }

        $packagedTotal = (int) $stock->packagings()->sum('jumlah');
        $distributedTotal = (int) $stock->distributed_stock;

        if ($packagedTotal > 0 && $distributedTotal >= $packagedTotal) {
            return 'Selesai disalurkan';
        }

        if ($distributedTotal > 0) {
            return 'Sebagian disalurkan';
        }

        if ($stock->packaged_stock > 0) {
            return 'Siap distribusi';
        }

        if ($stock->sorted_stock > 0) {
            return 'Menunggu packaging';
        }

        return 'Menunggu sorting';
    }

    private static function stockFlowColor(?StokPangan $stock): string
    {
        return match (static::stockFlowStatus($stock)) {
            'Selesai disalurkan' => 'success',
            'Sebagian disalurkan' => 'info',
            'Siap distribusi' => 'warning',
            'Menunggu packaging' => 'gray',
            default => 'danger',
        };
    }

    private static function isStockFullyDistributed(?StokPangan $stock): bool
    {
        if (!$stock) {
            return false;
        }

        $packagedTotal = (int) $stock->packagings()->sum('jumlah');

        return $packagedTotal > 0 && (int) $stock->distributed_stock >= $packagedTotal;
    }
}
