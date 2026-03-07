@extends('layouts.app')

@section('title', 'Konto reaktivieren')

@section('content')
    <div class="max-w-3xl mx-auto">
        <x-card>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Konto zur Löschung vorgemerkt</h2>

            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg mb-6">
                <p class="text-amber-800 font-medium mb-2">Ihr Konto ist zur Löschung vorgemerkt.</p>
                <p class="text-amber-700 text-sm">
                    In <strong>{{ $daysRemaining }} {{ $daysRemaining === 1 ? 'Tag' : 'Tagen' }}</strong> werden Ihre persönlichen Daten unwiderruflich anonymisiert.
                </p>
            </div>

            <p class="text-gray-600 mb-6">Möchten Sie Ihr Konto reaktivieren und die Löschung abbrechen?</p>

            <div class="flex items-center gap-4">
                <form method="POST" action="{{ route('reactivate.confirm') }}">
                    @csrf
                    <x-button type="submit" variant="primary">
                        Ja, Konto reaktivieren
                    </x-button>
                </form>

                <a href="{{ url('/pinnwand') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Nein, Löschung fortsetzen
                </a>
            </div>
        </x-card>
    </div>
@endsection
