@extends('layouts.app')

@section('title', 'Neues Passwort setzen')

@section('content')
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Neues Passwort setzen</h2>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Neues Passwort</label>
                        <input id="password" type="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent">
                    </div>

                    <button type="submit" class="w-full py-2 px-4 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors duration-200">
                        Passwort zurücksetzen
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection