<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\Satellite;
use Illuminate\Database\Seeder;

class MissionSatelliteSeeder extends Seeder
{
    /**
     * Seed missions and satellites for the take-home assignment.
     * Ensures: inactive (0 satellites), critical (any offline), unstable (>2 degraded), active (healthy).
     */
    public function run(): void
    {
        $europa = Mission::create(['name' => 'Europa Explorer']);
        Satellite::create(['mission_id' => $europa->id, 'health_status' => 'healthy']);
        Satellite::create(['mission_id' => $europa->id, 'health_status' => 'healthy']);
        Satellite::create(['mission_id' => $europa->id, 'health_status' => 'healthy']);
        Satellite::create(['mission_id' => $europa->id, 'health_status' => 'healthy']);

        $mars = Mission::create(['name' => 'Mars One']);
        Satellite::create(['mission_id' => $mars->id, 'health_status' => 'offline']);

        $titan = Mission::create(['name' => 'Titan Survey']);
        Satellite::create(['mission_id' => $titan->id, 'health_status' => 'degraded']);
        Satellite::create(['mission_id' => $titan->id, 'health_status' => 'degraded']);
        Satellite::create(['mission_id' => $titan->id, 'health_status' => 'degraded']);

        Mission::create(['name' => 'Lunar Outpost']); // 0 satellites -> inactive
    }
}
