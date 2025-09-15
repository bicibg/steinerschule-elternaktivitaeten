<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ShiftVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{

    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user()
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

        $volunteers = \App\Models\ShiftVolunteer::where('user_id', $user->id)
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
}