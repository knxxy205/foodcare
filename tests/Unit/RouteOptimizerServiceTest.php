<?php

namespace Tests\Unit;

use App\Models\PenerimaBantuan;
use App\Models\SimulasiGudang;
use App\Services\RouteOptimizerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteOptimizerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_generation_requires_optimal_warehouse_status(): void
    {
        SimulasiGudang::create([
            'jumlah_barang' => 100,
            'jumlah_relawan' => 1,
            'kapasitas_sorting' => 10,
            'kapasitas_packaging' => 10,
            'status_bottleneck' => 'Bottleneck',
        ]);

        $this->expectException(\RuntimeException::class);

        app(RouteOptimizerService::class)->generate(-6.9175, 107.6191);
    }

    public function test_route_generation_uses_nearest_distance_first(): void
    {
        SimulasiGudang::create([
            'jumlah_barang' => 10,
            'jumlah_relawan' => 5,
            'kapasitas_sorting' => 50,
            'kapasitas_packaging' => 50,
            'status_bottleneck' => 'Optimal',
        ]);

        $near = PenerimaBantuan::factory()->create([
            'nama_penerima' => 'Titik Dekat',
            'latitude' => -6.918,
            'longitude' => 107.619,
        ]);

        PenerimaBantuan::factory()->create([
            'nama_penerima' => 'Titik Jauh',
            'latitude' => -6.950,
            'longitude' => 107.650,
        ]);

        $route = app(RouteOptimizerService::class)->generate(-6.9175, 107.6191);

        $this->assertSame($near->id, $route->first()['id']);
    }
}

