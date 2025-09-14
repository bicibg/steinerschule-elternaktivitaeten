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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('activities.index') }}" class="flex items-center">
                        <svg class="h-8 sm:h-10 w-auto" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                            <text x="10" y="25" font-family="Arial, sans-serif" font-size="16" font-weight="300" fill="#4a90a4" letter-spacing="2px">rudolf</text>
                            <text x="10" y="45" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#4a90a4" letter-spacing="1px">steinerschule</text>
                            <text x="160" y="20" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 160 20)">bern</text>
                            <text x="170" y="20" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 170 20)">ittigen</text>
                            <text x="180" y="25" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 180 25)">langnau</text>
                        </svg>
                        <span class="ml-2 sm:ml-3 text-[#4a90a4] font-semibold text-sm sm:text-lg hidden sm:inline">Elternaktivitäten</span>
                    </a>
                </div>
                <div class="flex items-center space-x-2">
                    @if(!Auth::check())
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm border border-[#4a90a4] text-[#4a90a4] rounded hover:bg-[#4a90a4] hover:text-white transition-colors">Anmelden</a>
                        <form action="{{ route('demo.login') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">DEMO</button>
                        </form>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 text-sm border border-[#4a90a4] text-[#4a90a4] rounded hover:bg-[#4a90a4] hover:text-white transition-colors hidden sm:inline-block">Registrieren</a>
                    @else
                        <span class="text-sm text-[#4a90a4] mr-2">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-sm border border-gray-400 text-gray-600 rounded hover:bg-gray-100 transition-colors">Abmelden</button>
                        </form>
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