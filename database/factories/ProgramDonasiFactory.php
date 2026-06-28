<?php

namespace Database\Factories;

use App\Models\ProgramDonasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgramDonasi>
 */
class ProgramDonasiFactory extends Factory
{
    protected $model = ProgramDonasi::class;

    public function definition(): array
    {
        $target = fake()->numberBetween(10_000_000, 100_000_000);

        return [
            'nama_program' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'target_dana' => $target,
            'dana_terkumpul' => fake()->numberBetween(0, $target),
            'status' => fake()->randomElement(['aktif', 'selesai']),
            'foto' => null,
            'tanggal_berakhir' => fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
        ];
    }
}

