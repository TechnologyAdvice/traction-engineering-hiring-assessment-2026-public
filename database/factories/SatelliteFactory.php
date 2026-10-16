<?php

namespace Database\Factories;

use App\Models\Mission;
use App\Models\Satellite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Satellite>
 */
class SatelliteFactory extends Factory
{
    protected $model = Satellite::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mission_id' => Mission::factory(),
            'health_status' => fake()->randomElement(['healthy', 'degraded', 'offline']),
        ];
    }
}
