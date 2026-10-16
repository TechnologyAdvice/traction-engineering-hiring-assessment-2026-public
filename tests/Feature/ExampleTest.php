<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_api_missions_list_returns_200(): void
    {
        $this->seed(\Database\Seeders\MissionSatelliteSeeder::class);
        $response = $this->getJson('/api/missions');
        $response->assertStatus(200);
        $response->assertJsonPath('missions.0.id', 1);
    }
}
