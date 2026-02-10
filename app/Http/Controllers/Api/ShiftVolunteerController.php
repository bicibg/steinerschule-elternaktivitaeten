<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftVolunteer;
use App\Services\ShiftService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShiftVolunteerController extends Controller
{
    protected ShiftService $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    /**
     * Store a new volunteer signup for a shift.
     *
     * POST /api/shifts/{shift}/signup
     */
    public function store(Request $request, Shift $shift): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        try {
            $volunteer = $this->shiftService->signupForShift($shift, auth()->user());

            return response()->json([
                'success' => true,
                'volunteer' => [
                    'id' => $volunteer->id,
                    'name' => $volunteer->name,
                    'user_id' => $volunteer->user_id,
                ],
                'offline_filled' => $shift->offline_filled,
                'online_count' => $shift->volunteers()->count(),
                'needed' => $shift->needed,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove volunteer signup from a shift.
     *
     * DELETE /api/shifts/{shift}/withdraw
     */
    public function destroy(Shift $shift): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        try {
            $this->shiftService->withdrawFromShift($shift, auth()->user());

            return response()->json([
                'success' => true,
                'offline_filled' => $shift->offline_filled,
                'online_count' => $shift->volunteers()->count(),
                'needed' => $shift->needed,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * List volunteers for a shift.
     *
     * GET /api/shifts/{shift}/volunteers
     */
    public function index(Shift $shift): JsonResponse
    {
        $volunteers = $this->shiftService->getShiftVolunteers($shift);

        $isAuthenticated = auth()->check();

        return response()->json([
            'data' => $volunteers->map(function ($volunteer) use ($isAuthenticated) {
                $item = [
                    'name' => $isAuthenticated ? $volunteer->name : 'Angemeldet',
                    'created_at' => $volunteer->created_at->format('d.m.Y H:i'),
                ];

                if ($isAuthenticated) {
                    $item['id'] = $volunteer->id;
                }

                return $item;
            }),
        ]);
    }
}
