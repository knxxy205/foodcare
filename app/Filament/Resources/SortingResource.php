<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SortingResource\Pages;
use App\Models\Sorting;
use App\Models\StokPangan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SortingResource extends Resource
{
    protected static ?string $model = Sorting::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationLabel = 'Sorting';

    protected static ?string $modelLabel = 'Sorting';

    protected static ?string $pluralModelLabel = 'Sorting';

    protected static ?int $navigationSort = 5;

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
                            ->label('Stok Barang (Menunggu Sorting)')
                            ->options(fn () => StokPangan::all()
                                ->filter(fn ($stok) => $stok->raw_stock > 0 || request()->route('record')) // Allow existing record stock
                                ->mapWithKeys(fn ($stok) => [$stok->id => $stok->nama_barang . " (Sisa: " . $stok->raw_stock . ")"])
                            )
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah Disortir')
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
                                        $currentSorting = Sorting::find($currentRecordId);
                                        if ($currentSorting && $currentSorting->stok_id == $stokId) {
                                            $currentAmount = $currentSorting->jumlah;
                                        }
                                    }

                                    $available = $stok->raw_stock + $currentAmount;
                                    if ($value > $available) {
                                        $fail("Jumlah yang disortir ({$value}) melebihi stok mentah yang tersedia ({$available}).");
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
                // Relawan can only see their sorting tasks
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
                    ->label('Jumlah Disortir')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_alur')
                    ->label('Status Alur')
                    ->state(fn (Sorting $record): string => static::stockFlowStatus($record->stok))
                    ->badge()
                    ->color(fn (Sorting $record): string => static::stockFlowColor($record->stok)),
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
                    ->visible(fn (Sorting $record): bool => ! static::isStockFullyDistributed($record->stok)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Sorting $record): bool => ! static::isStockFullyDistributed($record->stok)),
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
            'index' => Pages\ListSortings::route('/'),
            'create' => Pages\CreateSorting::route('/create'),
            'edit' => Pages\EditSorting::route('/{record}/edit'),
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
