<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SimpananPokok>
 */
class SimpananPokokFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'iuran' => $this->faker->numberBetween(100000, 100000),
            'total_simpanan' => $this->faker->numberBetween(1000000, 10000000),
            'status_simpanan' => 'Belum Lunas',
            'created_at' => $this->faker->dateTimeBetween('2023-01-01', '2024-01-31'),
            'updated_at' => $this->faker->dateTimeBetween('2023-01-01', '2024-01-31'),
        ];
    }
}
