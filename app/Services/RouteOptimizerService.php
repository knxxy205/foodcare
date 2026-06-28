<?php

namespace App\Services;

use App\Models\PenerimaBantuan;
use App\Models\SimulasiGudang;
use Illuminate\Support\Collection;

class RouteOptimizerService
{
    public function canGenerateRoute(): bool
    {
        return SimulasiGudang::latest()->value('status_bottleneck') === 'Optimal';
    }

    public function generate(float $warehouseLat, float $warehouseLng): Collection
    {
        if (!$this->canGenerateRoute()) {
            throw new \RuntimeException('Route generation only allowed when warehouse status is Optimal.');
        }

        $unvisited = PenerimaBantuan::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->values();

        $route = collect();
        $currentLat = $warehouseLat;
        $currentLng = $warehouseLng;
        $step = 1;

        while ($unvisited->isNotEmpty()) {
            $closest = $unvisited
                ->map(fn (PenerimaBantuan $recipient, int $index) => [
                    'index' => $index,
                    'recipient' => $recipient,
                    'distance' => $this->distance($currentLat, $currentLng, (float) $recipient->latitude, (float) $recipient->longitude),
                ])
                ->sortBy('distance')
                ->first();

            /** @var PenerimaBantuan $recipient */
            $recipient = $closest['recipient'];
            $route->push([
                'step' => $step++,
                'id' => $recipient->id,
                'nama_penerima' => $recipient->nama_penerima,
                'alamat' => $recipient->alamat,
                'kontak' => $recipient->kontak,
                'latitude' => (float) $recipient->latitude,
                'longitude' => (float) $recipient->longitude,
                'distance' => round($closest['distance'], 2),
            ]);

            $currentLat = (float) $recipient->latitude;
            $currentLng = (float) $recipient->longitude;
            $unvisited->forget($closest['index']);
            $unvisited = $unvisited->values();
        }

        return $route;
    }

    private function distance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}

