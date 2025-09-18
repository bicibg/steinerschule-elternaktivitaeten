<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftVolunteer;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function signup(Request $request, Shift $shift)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bitte melden Sie sich an, um sich für Schichten anzumelden.');
        }

        // Check if total (offline + online) registrations have reached capacity
        $onlineCount = $shift->volunteers()->count();
        $totalRegistered = $shift->offline_filled + $onlineCount;

        if ($totalRegistered >= $shift->needed) {
            return back()->with('error', 'Diese Schicht ist bereits voll besetzt.');
        }

        $existing = ShiftVolunteer::where('shift_id', $shift->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Sie sind bereits für diese Schicht angemeldet.');
        }

        ShiftVolunteer::create([
            'shift_id' => $shift->id,
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);

        return back()->with('success', 'Sie haben sich erfolgreich für die Schicht angemeldet.');
    }

    public function withdraw(Shift $shift)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $volunteer = ShiftVolunteer::where('shift_id', $shift->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$volunteer) {
            return back()->with('error', 'Sie sind nicht für diese Schicht angemeldet.');
        }

        $volunteer->delete();
        // Don't decrement filled - it represents offline registrations only

        return back()->with('success', 'Sie haben sich erfolgreich von der Schicht abgemeldet.');
    }
}