@props([
    'name' => null,
    'email' => null,
    'phone' => null,
    'title' => 'Kontaktinformationen'
])

<div class="bg-steiner-lighter rounded-lg p-4">
    <h3 class="font-semibold text-gray-800 mb-3">{{ $title }}</h3>
    <div class="space-y-2 text-sm">
        @if($name)
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">{{ $name }}</span>
            </div>
        @endif

        @if($email)
            <div class="flex items-center" x-data="{ revealed: false }">
                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <div>
                    <button @click="revealed = true" x-show="!revealed" class="text-steiner-blue hover:text-steiner-dark underline">
                        E-Mail anzeigen
                    </button>
                    <a x-show="revealed" x-cloak href="mailto:{{ $email }}" class="text-steiner-blue hover:text-steiner-dark">
                        {{ $email }}
                    </a>
                </div>
            </div>
        @endif

        @if($phone)
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <a href="tel:{{ $phone }}" class="text-steiner-blue hover:text-steiner-dark">
                    {{ $phone }}
                </a>
            </div>
        @endif
    </div>
</div>