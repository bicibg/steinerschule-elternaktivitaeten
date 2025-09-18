<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
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
     * POST /api/shifts/{shift}/volunteers
     *
     * @param Request $request
     * @param Shift   $shift
     *
     * @return JsonResponse
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
                'data' => [
                    'volunteer' => [
                        'id' => $volunteer->id,
                        'name' => $volunteer->name,
                        'user_id' => $volunteer->user_id,
                    ],
                    'shift_stats' => $this->shiftService->getShiftStatistics($shift),
                ],
                'message' => 'Erfolgreich angemeldet',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove volunteer signup from a shift.
     *
     * DELETE /api/shifts/{shift}/volunteers
     *
     * @param Shift $shift
     *
     * @return JsonResponse
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
                'data' => [
                    'shift_stats' => $this->shiftService->getShiftStatistics($shift),
                ],
                'message' => 'Erfolgreich abgemeldet',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * List volunteers for a shift.
     *
     * GET /api/shifts/{shift}/volunteers
     *
     * @param Shift $shift
     *
     * @return JsonResponse
     */
    public function index(Shift $shift): JsonResponse
    {
        $volunteers = $this->shiftService->getShiftVolunteers($shift);

        return response()->json([
            'data' => $volunteers->map(function ($volunteer) {
                return [
                    'id' => $volunteer->id,
                    'name' => auth()->check() ? $volunteer->name : 'Angemeldet',
                    'created_at' => $volunteer->created_at->format('d.m.Y H:i'),
                ];
            }),
        ]);
    }
}