<?php

namespace Tests\Feature;

use Database\Seeders\MissionSatelliteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MissionSatelliteSeeder::class);
    }

    public function test_list_missions_returns_success(): void
    {
        $response = $this->getJson('/api/missions');

        $response->assertStatus(200);
        $response->assertJsonStructure(['missions' => [['id', 'name', 'satellite_count']]]);
    }

    public function test_mission_detail_returns_expected_keys(): void
    {
        $response = $this->getJson('/api/missions/1');

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'status', 'satellite_count']);
    }

    public function test_mission_not_found_returns_404(): void
    {
        $response = $this->getJson('/api/missions/99999');

        $response->assertStatus(404);
    }
}
