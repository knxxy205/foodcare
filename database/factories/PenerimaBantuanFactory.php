<?php

namespace Database\Factories;

use App\Models\PenerimaBantuan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenerimaBantuan>
 */
class PenerimaBantuanFactory extends Factory
{
    protected $model = PenerimaBantuan::class;

    public function definition(): array
    {
        return [
            'nama_penerima' => fake()->company(),
            'alamat' => fake()->address(),
            'kontak' => fake()->phoneNumber(),
            'latitude' => fake()->latitude(-6.98, -6.85),
            'longitude' => fake()->longitude(107.55, 107.70),
        ];
    }
}

