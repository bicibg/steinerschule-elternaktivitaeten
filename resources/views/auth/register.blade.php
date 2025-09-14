@extends('layouts.app')

@section('title', 'Registrieren')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Registrieren</h2>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" id="name" name="name" required autofocus
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4a90a4] focus:border-transparent"
                           value="{{ old('name') }}"
                           placeholder="z.B. Anna (2a)">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4a90a4] focus:border-transparent"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Passwort</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4a90a4] focus:border-transparent">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Passwort bestätigen</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#4a90a4] focus:border-transparent">
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-[#4a90a4] text-white rounded-md hover:bg-[#2c5aa0] transition-colors duration-200">
                    Registrieren
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Bereits ein Konto?
                    <a href="{{ route('login') }}" class="text-[#4a90a4] hover:text-[#2c5aa0]">
                        Jetzt anmelden
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection