<?php

namespace Database\Factories;

use App\Models\Distribusi;
use App\Models\PenerimaBantuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Distribusi>
 */
class DistribusiFactory extends Factory
{
    protected $model = Distribusi::class;

    public function definition(): array
    {
        return [
            'relawan_id' => User::factory()->state(['role' => 'relawan']),
            'penerima_id' => PenerimaBantuan::factory(),
            'nama_barang' => 'Default Item',
            'jumlah_paket' => fake()->numberBetween(5, 50),
            'status' => fake()->randomElement(['pending', 'dalam_proses', 'dikirim', 'selesai', 'gagal']),
            'tanggal' => fake()->dateTimeBetween('-30 days', '+14 days'),
            'foto_bukti' => null,
        ];
    }
}

