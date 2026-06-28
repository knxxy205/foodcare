<?php

namespace Database\Factories;

use App\Models\Donasi;
use App\Models\ProgramDonasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donasi>
 */
class DonasiFactory extends Factory
{
    protected $model = Donasi::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'donatur']),
            'program_id' => ProgramDonasi::factory(),
            'jumlah' => fake()->numberBetween(10_000, 2_000_000),
            'metode_pembayaran' => fake()->randomElement(['Transfer Bank BCA', 'Transfer Bank Mandiri', 'E-Wallet GoPay']),
            'status' => fake()->randomElement(['pending', 'success', 'failed']),
            'tanggal' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}

