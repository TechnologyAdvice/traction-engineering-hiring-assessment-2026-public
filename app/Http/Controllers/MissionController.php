<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Services\MissionStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function __construct(
        private MissionStatusService $statusService
    ) {
    }

    /**
     * GET /api/missions — List all missions.
     */
    public function index(): JsonResponse
    {
        $missions = Mission::withCount('satellites')->get();

        $data = $missions->map(fn (Mission $m) => [
            'id' => $m->id,
            'name' => $m->name,
            'satellite_count' => $m->satellites_count,
        ]);

        return response()->json(['missions' => $data]);
    }

    /**
     * GET /api/missions/{mission} — Mission detail.
     */
    public function show(Mission $mission): JsonResponse
    {
        $mission->loadCount('satellites');
        $status = $this->statusService->getStatusForMission($mission);

        return response()->json([
            'id' => $mission->id,
            'name' => $mission->name,
            'status' => $status,
            'satellite_count' => $mission->satellites_count,
        ]);
    }

    /**
     * POST /api/missions — Create a new mission.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $mission = Mission::create($validated);

        return response()->json([
            'id' => $mission->id,
            'name' => $mission->name,
        ], 201);
    }

    /**
     * GET /api/missions/{mission}/satellites — List satellites for a mission.
     */
    public function satellites(Mission $mission): JsonResponse
    {
        $satellites = $mission->satellites()->get();

        $data = $satellites->map(fn ($s) => [
            'id' => $s->id,
            'mission_id' => $s->mission_id,
            'health_status' => $s->health_status,
        ]);

        return response()->json(['satellites' => $data]);
    }
}
