<?php

namespace Database\Factories;

use App\Models\StokPangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StokPangan>
 */
class StokPanganFactory extends Factory
{
    protected $model = StokPangan::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(20, 500);
        $entryDate = fake()->dateTimeBetween('-30 days', 'now');

        return [
            'nama_barang' => fake()->randomElement(['Beras', 'Minyak Goreng', 'Mie Instan', 'Susu UHT', 'Telur']),
            'jumlah' => $quantity,
            'jumlah_awal' => $quantity,
            'jumlah_tersedia' => $quantity,
            'tanggal_masuk' => $entryDate,
            'tanggal_kadaluarsa' => fake()->dateTimeBetween('+14 days', '+12 months'),
        ];
    }
}

