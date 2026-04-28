<?php

namespace App\Services;

use App\Models\Mission;

class MissionStatusService
{
    /**
     * Determine mission status from satellite health.
     */
    public function getStatusForMission(Mission $mission): string
    {
        $satellites = $mission->satellites;
        $count = $satellites->count();

        if ($count === 0) {
            return 'inactive';
        }

        $offlineCount = $satellites->where('health_status', 'offline')->count();
        if ($offlineCount > 0) {
            return 'critical';
        }

        $degradedCount = $satellites->where('health_status', 'degraded')->count();
        if ($degradedCount >= 2) {
            return 'unstable';
        }

        return 'active';
    }
}
