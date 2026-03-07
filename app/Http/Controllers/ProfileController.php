<?php

namespace App\Http\Controllers;

use App\Models\ShiftVolunteer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Profil erfolgreich aktualisiert.');
    }

    public function show(User $user)
    {
        return view('profile.show', compact('user'));
    }

    public function shifts()
    {
        $user = auth()->user();

        $volunteers = ShiftVolunteer::where('user_id', $user->id)
            ->with(['shift.bulletinPost'])
            ->get();

        return view('profile.shifts', compact('volunteers'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Passwort erfolgreich geändert.');
    }

    public function requestDeletion(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->is_admin || $user->is_super_admin) {
            return redirect()->route('profile.edit')
                ->with('error', 'Administratoren können ihr eigenes Konto nicht löschen.');
        }

        $user->requestDeletion();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/pinnwand')
            ->with('success', 'Ihr Konto wurde zur Löschung vorgemerkt. Sie haben 30 Tage, um sich erneut anzumelden und die Löschung rückgängig zu machen.');
    }
}
