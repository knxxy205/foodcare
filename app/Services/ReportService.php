<?php

namespace App\Services;

use App\Models\Distribusi;
use App\Models\Donasi;
use App\Models\Packaging;
use App\Models\PembelianPangan;
use App\Models\Sorting;
use App\Models\StokPangan;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class ReportService
{
    public function generate(string $type, CarbonInterface $start, CarbonInterface $end): Collection
    {
        return match ($type) {
            'donasi' => Donasi::with(['user', 'program'])->whereBetween('tanggal', [$start, $end])->latest('tanggal')->get(),
            'pembelian' => PembelianPangan::whereBetween('tanggal_beli', [$start, $end])->latest('tanggal_beli')->get(),
            'stok' => StokPangan::whereBetween('tanggal_masuk', [$start, $end])->orderBy('tanggal_kadaluarsa')->get(),
            'distribusi' => Distribusi::with(['relawan', 'penerima'])->whereBetween('tanggal', [$start, $end])->latest('tanggal')->get(),
            'relawan' => $this->volunteerReport($start, $end),
            default => collect(),
        };
    }

    public function toCsv(Collection $rows): string
    {
        if ($rows->isEmpty()) {
            return '';
        }

        $normalized = $rows->map(fn ($row) => collect(is_array($row) ? $row : $row->toArray())->flatten()->all());
        $headers = array_keys($normalized->first());

        $lines = [implode(',', $headers)];

        foreach ($normalized as $row) {
            $lines[] = collect($headers)
                ->map(fn ($header) => '"' . str_replace('"', '""', (string) ($row[$header] ?? '')) . '"')
                ->implode(',');
        }

        return implode("\n", $lines);
    }

    private function volunteerReport(CarbonInterface $start, CarbonInterface $end): Collection
    {
        return User::where('role', 'relawan')->get()->map(fn (User $volunteer) => [
            'name' => $volunteer->name,
            'email' => $volunteer->email,
            'phone' => $volunteer->phone,
            'total_sorting' => Sorting::where('relawan_id', $volunteer->id)->whereBetween('waktu_proses', [$start, $end])->sum('jumlah'),
            'total_packaging' => Packaging::where('relawan_id', $volunteer->id)->whereBetween('waktu_proses', [$start, $end])->sum('jumlah'),
            'total_delivery' => Distribusi::where('relawan_id', $volunteer->id)->whereBetween('tanggal', [$start, $end])->count(),
        ]);
    }
}

