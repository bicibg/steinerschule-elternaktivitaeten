<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\ShiftService;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    private ShiftService $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function signup(Request $request, Shift $shift)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bitte melden Sie sich an, um sich für Schichten anzumelden.');
        }

        try {
            $this->shiftService->signupForShift($shift, auth()->user());
            return back()->with('success', 'Sie haben sich erfolgreich für die Schicht angemeldet.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function withdraw(Shift $shift)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        try {
            $this->shiftService->withdrawFromShift($shift, auth()->user());
            return back()->with('success', 'Sie haben sich erfolgreich von der Schicht abgemeldet.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}