<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Donasi;
use App\Models\PembelianPangan;
use App\Models\StokPangan;
use App\Models\Distribusi;
use App\Models\User;
use App\Models\Sorting;
use App\Models\Packaging;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Laporan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan Konsolidasi FoodCare';

    protected static ?int $navigationSort = 11;

    protected static string $view = 'filament.pages.laporan';

    // Page state
    public string $reportType = 'donasi';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public array $reportData = [];
    public bool $isGenerated = false;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->endOfDay()->toDateString();
    }

    public function generate()
    {
        $this->validate([
            'reportType' => 'required|string|in:donasi,pembelian,stok,distribusi,relawan',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        switch ($this->reportType) {
            case 'donasi':
                $this->reportData = Donasi::with(['user', 'program'])
                    ->whereBetween('tanggal', [$start, $end])
                    ->orderBy('tanggal', 'desc')
                    ->get()
                    ->toArray();
                break;
                
            case 'pembelian':
                $this->reportData = PembelianPangan::whereBetween('tanggal_beli', [$start, $end])
                    ->orderBy('tanggal_beli', 'desc')
                    ->get()
                    ->toArray();
                break;
                
            case 'stok':
                // Stocks are filtered by their entry date (tanggal_masuk)
                $this->reportData = StokPangan::whereBetween('tanggal_masuk', [$start, $end])
                    ->orderBy('tanggal_kadaluarsa', 'asc')
                    ->get()
                    ->map(fn ($stok) => [
                        'id' => $stok->id,
                        'nama_barang' => $stok->nama_barang,
                        'jumlah' => $stok->jumlah,
                        'raw_stock' => $stok->raw_stock,
                        'sorted_stock' => $stok->sorted_stock,
                        'packaged_stock' => $stok->packaged_stock,
                        'tanggal_masuk' => $stok->tanggal_masuk->toDateString(),
                        'tanggal_kadaluarsa' => $stok->tanggal_kadaluarsa->toDateString(),
                    ])
                    ->toArray();
                break;
                
            case 'distribusi':
                $this->reportData = Distribusi::with(['relawan', 'penerima'])
                    ->whereBetween('tanggal', [$start, $end])
                    ->orderBy('tanggal', 'desc')
                    ->get()
                    ->toArray();
                break;
                
            case 'relawan':
                // Volunteer activity: retrieve volunteer list and calculate their sort & package contributions
                $volunteers = User::where('role', 'relawan')->get();
                $data = [];
                
                foreach ($volunteers as $vol) {
                    $sorts = Sorting::where('relawan_id', $vol->id)
                        ->whereBetween('waktu_proses', [$start, $end])
                        ->sum('jumlah');
                        
                    $packages = Packaging::where('relawan_id', $vol->id)
                        ->whereBetween('waktu_proses', [$start, $end])
                        ->sum('jumlah');

                    $deliveries = Distribusi::where('relawan_id', $vol->id)
                        ->whereBetween('tanggal', [$start, $end])
                        ->count();

                    $data[] = [
                        'name' => $vol->name,
                        'email' => $vol->email,
                        'phone' => $vol->phone,
                        'total_sorting' => $sorts,
                        'total_packaging' => $packages,
                        'total_delivery' => $deliveries,
                    ];
                }
                
                $this->reportData = $data;
                break;
        }

        $this->isGenerated = true;
    }

    public function exportExcel(ReportService $reportService)
    {
        $this->generate();

        $filename = 'foodcare-' . $this->reportType . '-' . now()->format('Ymd-His') . '.csv';
        $csv = $reportService->toCsv(collect($this->reportData));

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf()
    {
        $this->generate();

        $pdf = Pdf::loadView('filament.pages.laporan-pdf', $this->pdfPayload())
            ->setPaper('a4', $this->reportType === 'stok' ? 'landscape' : 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $this->pdfFilename(), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function pdfPayload(): array
    {
        return [
            'title' => $this->reportTitles()[$this->reportType] ?? 'Laporan FoodCare',
            'reportType' => $this->reportType,
            'startDate' => Carbon::parse($this->startDate)->translatedFormat('d F Y'),
            'endDate' => Carbon::parse($this->endDate)->translatedFormat('d F Y'),
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
            'columns' => $this->pdfColumns(),
            'rows' => $this->pdfRows(),
            'summary' => $this->pdfSummary(),
        ];
    }

    private function pdfColumns(): array
    {
        return match ($this->reportType) {
            'donasi' => ['Tanggal', 'Donatur', 'Program', 'Metode', 'Status', 'Jumlah'],
            'pembelian' => ['Tanggal', 'Nama Barang', 'Jumlah', 'Harga Satuan', 'Total Belanja'],
            'stok' => ['Nama Barang', 'Stok Awal', 'Belum Disortir', 'Belum Dikemas', 'Siap Salurkan', 'Tanggal Masuk', 'Kadaluarsa'],
            'distribusi' => ['Tanggal', 'Relawan', 'Penerima Bantuan', 'Paket', 'Status'],
            'relawan' => ['Nama Relawan', 'Email', 'Kontak', 'Mensortir', 'Mengemas', 'Pengiriman'],
            default => [],
        };
    }

    private function pdfRows(): array
    {
        return collect($this->reportData)->map(function (array $row) {
            return match ($this->reportType) {
                'donasi' => [
                    $this->formatDateTime($row['tanggal'] ?? null),
                    $row['user']['name'] ?? 'Donatur',
                    $row['program']['nama_program'] ?? '-',
                    $row['metode_pembayaran'] ?? '-',
                    $row['status'] ?? '-',
                    $this->formatRupiah($row['jumlah'] ?? 0),
                ],
                'pembelian' => [
                    $this->formatDate($row['tanggal_beli'] ?? null),
                    $row['nama_barang'] ?? '-',
                    number_format((float) ($row['jumlah'] ?? 0), 0, ',', '.'),
                    $this->formatRupiah($row['harga_satuan'] ?? 0),
                    $this->formatRupiah($row['total_harga'] ?? 0),
                ],
                'stok' => [
                    $row['nama_barang'] ?? '-',
                    number_format((float) ($row['jumlah'] ?? 0), 0, ',', '.'),
                    number_format((float) ($row['raw_stock'] ?? 0), 0, ',', '.'),
                    number_format((float) ($row['sorted_stock'] ?? 0), 0, ',', '.'),
                    number_format((float) ($row['packaged_stock'] ?? 0), 0, ',', '.'),
                    $this->formatDate($row['tanggal_masuk'] ?? null),
                    $this->formatDate($row['tanggal_kadaluarsa'] ?? null),
                ],
                'distribusi' => [
                    $this->formatDate($row['tanggal'] ?? null),
                    $row['relawan']['name'] ?? 'Relawan',
                    $row['penerima']['nama_penerima'] ?? '-',
                    number_format((float) ($row['jumlah_paket'] ?? 0), 0, ',', '.'),
                    $row['status'] ?? '-',
                ],
                'relawan' => [
                    $row['name'] ?? '-',
                    $row['email'] ?? '-',
                    $row['phone'] ?? '-',
                    number_format((float) ($row['total_sorting'] ?? 0), 0, ',', '.'),
                    number_format((float) ($row['total_packaging'] ?? 0), 0, ',', '.'),
                    number_format((float) ($row['total_delivery'] ?? 0), 0, ',', '.'),
                ],
                default => [],
            };
        })->all();
    }

    private function pdfSummary(): array
    {
        $rows = collect($this->reportData);

        return match ($this->reportType) {
            'donasi' => [
                'label' => 'Total Donasi Sukses',
                'value' => $this->formatRupiah($rows->where('status', 'success')->sum('jumlah')),
            ],
            'pembelian' => [
                'label' => 'Total Belanja Logistik',
                'value' => $this->formatRupiah($rows->sum('total_harga')),
            ],
            'stok' => [
                'label' => 'Total Paket Siap Salurkan',
                'value' => number_format((float) $rows->sum('packaged_stock'), 0, ',', '.') . ' paket',
            ],
            'distribusi' => [
                'label' => 'Total Paket Berhasil Tersalurkan',
                'value' => number_format((float) $rows->where('status', 'selesai')->sum('jumlah_paket'), 0, ',', '.') . ' paket',
            ],
            'relawan' => [
                'label' => 'Total Aktivitas Relawan',
                'value' => number_format((float) ($rows->sum('total_sorting') + $rows->sum('total_packaging') + $rows->sum('total_delivery')), 0, ',', '.') . ' aktivitas',
            ],
            default => ['label' => 'Total Data', 'value' => number_format($rows->count(), 0, ',', '.')],
        };
    }

    private function reportTitles(): array
    {
        return [
            'donasi' => 'Laporan Donasi Masuk',
            'pembelian' => 'Laporan Pembelian Pangan',
            'stok' => 'Laporan Stok Gudang',
            'distribusi' => 'Laporan Distribusi Bantuan',
            'relawan' => 'Laporan Aktivitas Relawan',
        ];
    }

    private function pdfFilename(): string
    {
        return 'foodcare-' . $this->reportType . '-' . now()->format('Ymd-His') . '.pdf';
    }

    private function formatRupiah(mixed $value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    private function formatDate(mixed $value): string
    {
        return $value ? Carbon::parse($value)->format('d M Y') : '-';
    }

    private function formatDateTime(mixed $value): string
    {
        return $value ? Carbon::parse($value)->format('d M Y H:i') : '-';
    }
}
