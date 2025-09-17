<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Elternaktivitäten') - Steinerschule Langnau</title>

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon.ico">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <header class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('bulletin.index') }}" class="flex items-center">
                        <svg class="h-8 sm:h-10 w-auto" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                            <text x="10" y="25" font-family="Arial, sans-serif" font-size="16" font-weight="300" fill="#4a90a4" letter-spacing="2px">rudolf</text>
                            <text x="10" y="45" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="#4a90a4" letter-spacing="1px">steinerschule</text>
                            <text x="160" y="20" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 160 20)">bern</text>
                            <text x="170" y="20" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 170 20)">ittigen</text>
                            <text x="180" y="25" font-family="Arial, sans-serif" font-size="8" fill="#4a90a4" transform="rotate(-90 180 25)">langnau</text>
                        </svg>
                        <span class="ml-2 sm:ml-3 text-steiner-blue font-semibold text-sm sm:text-lg hidden sm:inline">Elternaktivitäten</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-2 xl:space-x-3">
                    <a href="{{ route('bulletin.index') }}"
                       class="px-2 xl:px-3 py-1.5 text-xs xl:text-sm {{ request()->routeIs('bulletin.*') ? 'text-steiner-blue border-b-2 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue' }} transition-colors">
                        Pinnwand
                    </a>
                    <a href="{{ route('activities.index') }}"
                       class="px-2 xl:px-3 py-1.5 text-xs xl:text-sm {{ request()->routeIs('activities.*') ? 'text-steiner-blue border-b-2 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue' }} transition-colors">
                        Elternaktivitäten
                    </a>
                    <a href="{{ route('calendar.index') }}"
                       class="px-2 xl:px-3 py-1.5 text-xs xl:text-sm {{ request()->routeIs('calendar.*') ? 'text-steiner-blue border-b-2 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue' }} transition-colors">
                        Schichtkalender
                    </a>
                    <a href="{{ route('school-calendar.index') }}"
                       class="px-2 xl:px-3 py-1.5 text-xs xl:text-sm {{ request()->routeIs('school-calendar.*') ? 'text-steiner-blue border-b-2 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue' }} transition-colors">
                        Schulkalender
                    </a>
                </nav>

                <!-- Desktop User Menu -->
                <div class="hidden lg:flex items-center" x-data="{ userMenuOpen: false }">
                    @if(!Auth::check())
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm border border-steiner-blue text-steiner-blue rounded hover:bg-steiner-lighter hover:border-steiner-dark transition-colors">Anmelden</a>
                        <a href="{{ route('register') }}" class="ml-2 px-3 py-1.5 text-sm border border-steiner-blue text-steiner-blue rounded hover:bg-steiner-lighter hover:border-steiner-dark transition-colors">Registrieren</a>
                    @else
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen"
                                    class="flex items-center text-sm text-steiner-blue hover:text-steiner-dark transition-colors px-2 py-1">
                                <span class="truncate">{{ Auth::user()->name }}</span>
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="userMenuOpen"
                                 @click.away="userMenuOpen = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-50 overflow-hidden">
                                <!-- User Info Header -->
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <!-- Profile Actions -->
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profil bearbeiten
                                    </a>
                                    <a href="{{ route('profile.shifts') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Meine Schichten
                                    </a>
                                </div>

                                @if(Auth::user()->is_admin)
                                    <div class="border-t border-gray-200">
                                        <a href="/admin" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Admin Panel
                                        </a>
                                    </div>
                                @endif

                                <!-- Logout -->
                                <div class="border-t border-gray-200">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Abmelden
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Mobile Hamburger Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 rounded-md text-gray-600 hover:text-steiner-blue hover:bg-gray-100 transition-colors">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen"
                 x-cloak
                 @click.away="mobileMenuOpen = false"
                 class="lg:hidden border-t border-gray-200">
                <!-- Main Navigation Links -->
                <nav class="py-2 bg-white">
                    <a href="{{ route('bulletin.index') }}"
                       class="block px-3 py-2 text-sm {{ request()->routeIs('bulletin.*') ? 'text-steiner-blue bg-gray-50 border-l-4 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue hover:bg-gray-50' }} transition-colors">
                        Pinnwand
                    </a>
                    <a href="{{ route('activities.index') }}"
                       class="block px-3 py-2 text-sm {{ request()->routeIs('activities.*') ? 'text-steiner-blue bg-gray-50 border-l-4 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue hover:bg-gray-50' }} transition-colors">
                        Elternaktivitäten
                    </a>
                    <a href="{{ route('calendar.index') }}"
                       class="block px-3 py-2 text-sm {{ request()->routeIs('calendar.*') ? 'text-steiner-blue bg-gray-50 border-l-4 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue hover:bg-gray-50' }} transition-colors">
                        Schichtkalender
                    </a>
                    <a href="{{ route('school-calendar.index') }}"
                       class="block px-3 py-2 text-sm {{ request()->routeIs('school-calendar.*') ? 'text-steiner-blue bg-gray-50 border-l-4 border-steiner-blue' : 'text-gray-600 hover:text-steiner-blue hover:bg-gray-50' }} transition-colors">
                        Schulkalender
                    </a>
                </nav>

                <!-- Thick separator between navigation and user menu -->
                <div class="h-2 bg-gray-100 border-y border-gray-200"></div>

                <!-- User Menu Section -->
                <div class="py-2 bg-gray-50">
                    @if(!Auth::check())
                        <a href="{{ route('login') }}"
                           class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white transition-colors">
                            Anmelden
                        </a>
                        <a href="{{ route('register') }}"
                           class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white transition-colors">
                            Registrieren
                        </a>
                    @else
                        <div class="px-3 py-3 bg-white border-b border-gray-200">
                            <p class="font-medium text-sm text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white transition-colors">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil bearbeiten
                        </a>
                        <a href="{{ route('profile.shifts') }}"
                           class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white transition-colors">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Meine Schichten
                        </a>
                        @if(Auth::user()->is_admin)
                            <div class="border-t border-gray-200 mt-1 pt-1">
                                <a href="/admin"
                                   class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Admin Panel
                                </a>
                            </div>
                        @endif
                        <div class="border-t border-gray-200 mt-1 pt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="flex items-center w-full px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-white transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Abmelden
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Notifications -->
    @auth
        @php
            // Get all active notifications
            $allActiveNotifications = \App\Models\Notification::active()->get();

            // Separate priority and regular notifications
            $priorityNotifications = $allActiveNotifications->where('is_priority', true)
                ->filter(function($notification) {
                    return !$notification->isDismissedBy(auth()->id());
                });

            $regularNotifications = $allActiveNotifications->where('is_priority', false)
                ->filter(function($notification) {
                    return !$notification->isDismissedBy(auth()->id());
                })
                ->sortByDesc('created_at')
                ->take(3); // Show only last 3 regular notifications

            // Combine priority and regular notifications
            $activeNotifications = $priorityNotifications->concat($regularNotifications)
                ->sortByDesc(function($notification) {
                    // Priority notifications first, then by created_at
                    return ($notification->is_priority ? '1' : '0') . $notification->created_at->format('YmdHis');
                });
        @endphp

        @if($activeNotifications->count() > 0)
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="space-y-3 mt-4">
                    @foreach($activeNotifications as $notification)
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="relative bg-white rounded-lg shadow-md border-l-4 border-{{ $notification->type_color }}-400 p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-{{ $notification->type_color }}-100 rounded-full p-2">
                                    <svg class="h-5 w-5 text-{{ $notification->type_color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notification->type_icon }}"></path>
                                    </svg>
                                </div>
                                <div class="ml-6 flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        {{ $notification->title }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $notification->message }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <button @click="dismissNotification({{ $notification->id }}, $event)"
                                            type="button"
                                            aria-label="Schliessen"
                                            class="inline-flex rounded-full p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:ring-offset-2 transition-all">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                function dismissNotification(notificationId, event) {
                    const button = event.currentTarget;
                    const notificationElement = button.closest('[x-data]');

                    fetch(`/api/notifications/${notificationId}/dismiss`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            // Hide the notification using Alpine.js
                            Alpine.evaluate(notificationElement, 'show = false');
                        }
                    });
                }
            </script>
        @endif
    @endauth

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