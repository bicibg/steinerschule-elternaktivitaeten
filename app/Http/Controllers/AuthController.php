<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check for soft-deleted users with pending deletion requests
        $deletedUser = User::withTrashed()
            ->where('email', $credentials['email'])
            ->whereNotNull('deletion_requested_at')
            ->whereNull('anonymized_at')
            ->first();

        if ($deletedUser && Hash::check($credentials['password'], $deletedUser->password)) {
            $request->session()->put('reactivation_pending', $deletedUser->id);

            return redirect()->route('reactivate');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/pinnwand');
        }

        return back()->withErrors([
            'email' => 'Die angegebenen Anmeldedaten sind ungültig.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect('/pinnwand');
    }

    public function showReactivation(Request $request)
    {
        $userId = $request->session()->get('reactivation_pending');

        if (! $userId) {
            return redirect('/login');
        }

        $user = User::withTrashed()->find($userId);

        if (! $user || ! $user->isDeletionRequested()) {
            $request->session()->forget('reactivation_pending');

            return redirect('/login');
        }

        return view('auth.reactivate', [
            'daysRemaining' => $user->daysUntilAnonymization(),
        ]);
    }

    public function reactivate(Request $request)
    {
        $userId = $request->session()->pull('reactivation_pending');

        if (! $userId) {
            return redirect('/login');
        }

        $user = User::withTrashed()->find($userId);

        if (! $user || ! $user->isDeletionRequested()) {
            return redirect('/login');
        }

        $user->cancelDeletion();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/pinnwand')
            ->with('success', 'Ihr Konto wurde erfolgreich reaktiviert.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/pinnwand');
    }
}
