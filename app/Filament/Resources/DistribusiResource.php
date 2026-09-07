<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistribusiResource\Pages;
use App\Models\Distribusi;
use App\Models\SimulasiGudang;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DistribusiResource extends Resource
{
    protected static ?string $model = Distribusi::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Distribusi';

    protected static ?string $modelLabel = 'Distribusi';

    protected static ?string $pluralModelLabel = 'Distribusi';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        $latestStatus = SimulasiGudang::latest()->value('status_bottleneck');
        $routeAllowed = $latestStatus === 'Optimal';

        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('status_gudang')
                            ->label('Status Gudang')
                            ->content(fn () => '<strong>Status Gudang:</strong> ' . e($latestStatus ?? 'Belum disimulasikan'))
                            ->visible(! $routeAllowed),
                        Forms\Components\Select::make('relawan_id')
                            ->label('Relawan Penanggung Jawab')
                            ->options(fn () => User::where('role', 'relawan')->pluck('name', 'id'))
                            ->default(fn () => auth()->user()->role === 'relawan' ? auth()->id() : null)
                            ->disabled(!$isAdmin)
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('penerima_id')
                            ->relationship('penerima', 'nama_penerima')
                            ->label('Penerima Bantuan')
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit' && !$isAdmin),
                        Forms\Components\Select::make('nama_barang')
                            ->label('Barang yang Ingin Didistribusikan')
                            ->options(fn () => \App\Models\StokPangan::all()
                                ->groupBy('nama_barang')
                                ->mapWithKeys(function ($stocks, string $namaBarang) {
                                    $readyStock = $stocks->sum(fn ($stok) => $stok->packaged_stock);

                                    return $readyStock > 0
                                        ? [$namaBarang => $namaBarang . ' (' . $readyStock . ' item siap salur)']
                                        : [];
                                })
                                ->toArray()
                            )
                            ->searchable()
                            ->required()
                            ->live()
                            ->disabled(fn ($context) => $context === 'edit' && !$isAdmin),
                        Forms\Components\TextInput::make('jumlah_paket')
                            ->label('Jumlah Item')
                            ->numeric()
                            ->required()
                            ->rules([
                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $namaBarang = $get('nama_barang');
                                    if (!$namaBarang) {
                                        return;
                                    }
                                    
                                    $stocks = \App\Models\StokPangan::where('nama_barang', $namaBarang)->get();
                                    if ($stocks->isEmpty()) {
                                        $fail("Barang tidak ditemukan.");
                                        return;
                                    }

                                    $packagedStock = $stocks->sum(fn ($stok) => $stok->packaged_stock);
                                    if ($value > $packagedStock) {
                                        $fail("Jumlah item ({$value}) melebihi stok yang sudah di-packaging ({$packagedStock}).");
                                    }
                                },
                            ])
                            ->disabled(fn ($context) => $context === 'edit' && !$isAdmin),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Penyaluran')
                            ->default(now())
                            ->required()
                            ->disabled(fn ($context) => $context === 'edit' && !$isAdmin),
                        Forms\Components\Select::make('status')
                            ->label('Status Distribusi')
                            ->options([
                                'pending' => 'Pending',
                                'dalam_proses' => 'Dalam Proses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai (Tersalurkan)',
                                'gagal' => 'Gagal',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\FileUpload::make('foto_bukti')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->label('Foto Bukti Distribusi')
                            ->directory('bukti_distribusi')
                            ->image()
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->modifyQueryUsing(function ($query) use ($user) {
                // Relawan can only see distributions assigned to them
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
                Tables\Columns\TextColumn::make('penerima.nama_penerima')
                    ->label('Penerima Bantuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_barang')
                    ->label('Barang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_paket')
                    ->label('Jumlah Item')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'dalam_proses' => 'info',
                        'dikirim' => 'primary',
                        'pending' => 'warning',
                        'gagal' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto_bukti')
                    ->label('Bukti'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'dalam_proses' => 'Dalam Proses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'gagal' => 'Gagal',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->role === 'admin'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->visible(fn () => auth()->user()->role === 'admin'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDistribusis::route('/'),
            'create' => Pages\CreateDistribusi::route('/create'),
            'edit' => Pages\EditDistribusi::route('/{record}/edit'),
        ];
    }
}
