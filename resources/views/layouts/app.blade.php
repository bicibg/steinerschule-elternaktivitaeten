<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Elternaktivitäten') - Steinerschule Langnau</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center">
                    <a href="{{ route('activities.index') }}" class="flex items-center">
                        <svg class="h-7 sm:h-10 w-auto" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                            <text x="10" y="25" font-family="Arial, sans-serif" font-size="16" font-weight="300" fill="#4a90a4" letter-spacing="2px">rudolf</text>
                            <text x="10" y="45" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#4a90a4" letter-spacing="1px">steinerschule</text>
                            <text x="160" y="20" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 160 20)">bern</text>
                            <text x="170" y="20" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 170 20)">ittigen</text>
                            <text x="180" y="25" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 180 25)">langnau</text>
                        </svg>
                        <span class="ml-2 sm:ml-3 text-steiner-blue font-semibold text-sm sm:text-lg hidden lg:inline">Elternaktivitäten</span>
                    </a>
                </div>

                <!-- Navigation Tabs -->
                <nav class="flex items-center space-x-1 sm:space-x-3">
                    <a href="{{ route('activities.index') }}"
                       class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm {{ request()->routeIs('activities.*') ? 'text-steiner-blue border-b-2 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue' }} transition-colors">
                        Aktivitäten
                    </a>
                    <a href="{{ route('calendar.index') }}"
                       class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm {{ request()->routeIs('calendar.*') ? 'text-steiner-blue border-b-2 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue' }} transition-colors">
                        Kalender
                    </a>
                    <a href="{{ route('school-calendar.index') }}"
                       class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm {{ request()->routeIs('school-calendar.*') ? 'text-steiner-blue border-b-2 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue' }} transition-colors">
                        Schulkalender
                    </a>
                </nav>

                <div class="flex items-center" x-data="{ open: false }">
                    @if(!Auth::check())
                        <a href="{{ route('login') }}" class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-steiner-blue text-steiner-blue rounded hover:bg-steiner-blue hover:text-white transition-colors">Anmelden</a>
                        <a href="{{ route('register') }}" class="ml-2 px-3 py-1.5 text-sm border border-steiner-blue text-steiner-blue rounded hover:bg-steiner-blue hover:text-white transition-colors hidden sm:inline-block">Registrieren</a>
                    @else
                        <div class="relative">
                            <button @click="open = !open"
                                    class="flex items-center text-xs sm:text-sm text-steiner-blue hover:text-steiner-dark transition-colors px-2 py-1">
                                <span class="max-w-[100px] sm:max-w-none truncate">{{ Auth::user()->name }}</span>
                                <svg class="ml-1 h-3 w-3 sm:h-4 sm:w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Abmelden
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-500 text-sm">
                © {{ date('Y') }} Steinerschule Langnau. Alle Rechte vorbehalten.
            </p>
        </div>
    </footer>
</body>
</html>