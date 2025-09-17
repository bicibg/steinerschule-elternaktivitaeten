<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Last Reset Information --}}
        @if($lastReset = $this->getLastResetInfo())
            <x-filament::section>
                <x-slot name="heading">
                    Letzte Schuljahresvorbereitung
                </x-slot>
                <div class="text-sm space-y-2">
                    <p><strong>Datum:</strong> {{ $lastReset['date'] }} (vor {{ $lastReset['daysAgo'] }} Tagen)</p>
                    <p><strong>Durchgeführt von:</strong> {{ $lastReset['user'] }}</p>
                    <p><strong>Schuljahr:</strong> {{ $lastReset['schoolYear'] }}</p>
                    <p class="text-xs text-gray-600">
                        {{ $lastReset['activities'] }} Aktivitäten,
                        {{ $lastReset['bulletinPosts'] ?? 0 }} Pinnwand-Einträge,
                        {{ $lastReset['announcements'] ?? 0 }} Ankündigungen deaktiviert,
                        {{ $lastReset['posts'] }} Beiträge und
                        {{ $lastReset['comments'] }} Kommentare archiviert
                    </p>
                </div>
            </x-filament::section>
        @endif

        {{-- Warning Banner --}}
        <div class="bg-danger-50 border-4 border-danger-300 rounded-xl p-6">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <svg class="h-12 w-12 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-danger-800">
                        ⚠️ KRITISCHE SYSTEMFUNKTION
                    </h3>
                    <p class="text-danger-700 mt-2">
                        Diese Funktion bereitet das System für den Start eines neuen Schuljahres vor.
                        Diese Aktion kann <strong>NICHT</strong> rückgängig gemacht werden!
                    </p>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        @php
            $activeActivities = \App\Models\Activity::where('is_active', true)->count();
            $activeBulletinPosts = \App\Models\BulletinPost::where('status', 'published')->count();
            $activeAnnouncements = \App\Models\Announcement::where('is_active', true)->where('is_priority', false)->count();
            $activePosts = \App\Models\Post::whereNull('deleted_at')->count();
            $activeComments = \App\Models\Comment::whereNull('deleted_at')->count();
        @endphp
        <x-filament::section>
            <x-slot name="heading">
                Aktuelle Statistiken
            </x-slot>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-warning-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-warning-700">
                        {{ $activeActivities }}
                    </div>
                    <div class="text-sm text-warning-600">
                        {{ $activeActivities === 1 ? 'Aktivität' : 'Aktivitäten' }}
                    </div>
                </div>
                <div class="bg-warning-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-warning-700">
                        {{ $activeBulletinPosts }}
                    </div>
                    <div class="text-sm text-warning-600">
                        Pinnwand-{{ $activeBulletinPosts === 1 ? 'Eintrag' : 'Einträge' }}
                    </div>
                </div>
                <div class="bg-warning-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-warning-700">
                        {{ $activeAnnouncements }}
                    </div>
                    <div class="text-sm text-warning-600">
                        {{ $activeAnnouncements === 1 ? 'Ankündigung' : 'Ankündigungen' }}
                    </div>
                </div>
                <div class="bg-warning-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-warning-700">
                        {{ $activePosts }}
                    </div>
                    <div class="text-sm text-warning-600">
                        {{ $activePosts === 1 ? 'Forumbeitrag' : 'Forumbeiträge' }}
                    </div>
                </div>
                <div class="bg-warning-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-warning-700">
                        {{ $activeComments }}
                    </div>
                    <div class="text-sm text-warning-600">
                        {{ $activeComments === 1 ? 'Kommentar' : 'Kommentare' }}
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- Reset Form --}}
        <form wire:submit.prevent="performReset">
            {{ $this->form }}

            <div class="mt-6 flex justify-end">
                @if(\App\Models\AuditLog::wasActionPerformedRecently('year_reset', 30))
                    <x-filament::button
                        type="button"
                        color="gray"
                        disabled
                    >
                        Vorbereitung nicht möglich (wurde kürzlich durchgeführt)
                    </x-filament::button>
                @else
                    <x-filament::button
                        type="submit"
                        color="danger"
                        size="xl"
                        wire:loading.attr="disabled"
                        wire:confirm="LETZTE WARNUNG: Sind Sie absolut sicher, dass Sie das System für das neue Schuljahr vorbereiten möchten? Diese Aktion kann NICHT rückgängig gemacht werden!"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Neues Schuljahr jetzt vorbereiten
                    </x-filament::button>
                @endif
            </div>
        </form>
    </div>
</x-filament-panels::page>