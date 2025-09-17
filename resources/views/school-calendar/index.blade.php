@extends('layouts.app')

@section('title', 'Schulkalender')

@section('content')
<div x-data="{
    loading: false,
    currentMonth: {{ $date->month }},
    currentYear: {{ $date->year }},
    touchStartX: 0,
    touchStartY: 0,
    touchEndX: 0,
    touchEndY: 0,

    init() {
        // Make navigation function available globally
        window.calendarNav = this;

        // Add swipe listeners to calendar
        this.$nextTick(() => {
            const calendarEl = document.querySelector('#calendar-content');
            if (calendarEl) {
                calendarEl.addEventListener('touchstart', (e) => this.handleTouchStart(e), {passive: true});
                calendarEl.addEventListener('touchend', (e) => this.handleTouchEnd(e), {passive: true});
            }
        });
    },

    handleTouchStart(e) {
        this.touchStartX = e.changedTouches[0].screenX;
        this.touchStartY = e.changedTouches[0].screenY;
    },

    handleTouchEnd(e) {
        this.touchEndX = e.changedTouches[0].screenX;
        this.touchEndY = e.changedTouches[0].screenY;
        this.handleSwipe();
    },

    handleSwipe() {
        const swipeThreshold = 50; // Minimum distance for swipe
        const verticalThreshold = 100; // Maximum vertical movement allowed

        const deltaX = this.touchEndX - this.touchStartX;
        const deltaY = Math.abs(this.touchEndY - this.touchStartY);

        // Check if swipe is mostly horizontal
        if (Math.abs(deltaX) > swipeThreshold && deltaY < verticalThreshold) {
            if (deltaX > 0) {
                // Swiped right - go to previous month
                const prevButton = document.querySelector('#calendar-content button:first-of-type');
                if (prevButton) prevButton.click();
            } else {
                // Swiped left - go to next month
                const nextButton = document.querySelector('#calendar-content button:last-of-type');
                if (nextButton) nextButton.click();
            }
        }
    },

    async navigateToMonth(url) {
        if (this.loading) return;
        this.loading = true;

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Navigation failed');

            const html = await response.text();

            // Parse the HTML to extract just the inner content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Get the calendar content container
            const oldContent = document.querySelector('#calendar-content');
            if (!oldContent) {
                console.error('Calendar content not found');
                this.loading = false;
                return;
            }

            // Fade out
            oldContent.style.transition = 'opacity 0.15s';
            oldContent.style.opacity = '0.5';

            setTimeout(() => {
                // Replace only the inner HTML of the calendar-content div
                oldContent.innerHTML = html;

                // Fade in
                oldContent.style.opacity = '1';

                // Update URL without reload
                window.history.pushState({}, '', url);

                // Re-attach swipe listeners after content update
                const calendarEl = document.querySelector('#calendar-content');
                if (calendarEl) {
                    // Remove old listeners first to avoid duplicates
                    const newEl = calendarEl.cloneNode(true);
                    calendarEl.parentNode.replaceChild(newEl, calendarEl);

                    // Add new listeners
                    newEl.addEventListener('touchstart', (e) => this.handleTouchStart(e), {passive: true});
                    newEl.addEventListener('touchend', (e) => this.handleTouchEnd(e), {passive: true});
                }

                this.loading = false;
            }, 150);
        } catch (error) {
            console.error('Navigation error:', error);
            // Fallback to normal navigation
            window.location.href = url;
        }
    }
}" x-init="init()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Schulkalender</h1>
        <p class="text-gray-600">
            Hier finden Sie alle <strong>offiziellen Schultermine und Veranstaltungen</strong> der Steinerschule.
            Ferien, Feste, Aufführungen und wichtige Termine im Überblick.
        </p>
    </div>

    <x-info-box type="info">
        <strong>Offizielle Schultermine</strong> – Dieser Kalender zeigt Veranstaltungen, die von der Schule organisiert werden.
        <br>
        <span class="text-xs">Für Helfereinsätze siehe <a href="{{ route('calendar.index') }}" class="underline text-steiner-blue hover:text-steiner-dark">Schichtkalender</a></span>
    </x-info-box>

    <!-- Calendar Container -->
    <div id="calendar-content">
    <x-card noPadding="true">
        <!-- Month Navigation -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <button @click="navigateToMonth('{{ route('school-calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}')"
                   class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <h2 class="text-xl font-semibold text-gray-800">
                {{ $date->locale('de')->monthName . ' ' . $date->year }}
            </h2>

            <button @click="navigateToMonth('{{ route('school-calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}')"
                   class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Calendar Grid -->
        <div class="p-4">
            <!-- Weekday Headers -->
            <div class="grid grid-cols-7 gap-px bg-gray-200 mb-px">
                @foreach(['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'] as $day)
                    <div class="bg-gray-50 py-1 px-2 text-center text-sm font-medium text-gray-700">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-px bg-gray-200" style="grid-auto-rows: minmax(120px, 1fr);">
                @php
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();
                    $startDate = $startOfMonth->copy()->startOfWeek();
                    $endDate = $endOfMonth->copy()->endOfWeek();
                    $currentDate = $startDate->copy();
                @endphp

                @while($currentDate <= $endDate)
                    @php
                        $isCurrentMonth = $currentDate->month === $date->month;
                        $isToday = $currentDate->isToday();
                        $dateKey = $currentDate->format('Y-m-d');
                        $dayEvents = $eventsByDate->get($dateKey, collect());
                    @endphp

                    <div class="bg-white h-[120px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
                        <div class="h-full flex flex-col">
                            <div class="font-medium text-sm px-1 pt-0.5 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                {{ $currentDate->day }}
                            </div>

                            <div class="flex-1 overflow-hidden">
                                @foreach($dayEvents as $eventData)
                                    @php
                                        $event = $eventData['event'];
                                        // Don't treat any events as spanning - show each day separately with full rounded corners
                                        $roundedClass = 'rounded';

                                        // Generate color based on event type
                                        $colorClass = match($event->event_type) {
                                            'festival' => 'bg-red-500',
                                            'meeting' => 'bg-blue-500',
                                            'performance' => 'bg-purple-500',
                                            'holiday' => 'bg-gray-500',
                                            'sports' => 'bg-green-500',
                                            'excursion' => 'bg-yellow-500',
                                            default => 'bg-steiner-blue'
                                        };
                                    @endphp

                                    <a href="{{ route('school-calendar.show', $event) }}"
                                       class="block text-xs px-0.5 mb-px {{ $roundedClass }} {{ $colorClass }} text-white hover:opacity-75 transition-opacity truncate"
                                       title="{{ $event->title }}{{ $event->location ? ' - ' . $event->location : '' }}">
                                        {{ $event->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @php $currentDate->addDay(); @endphp
                @endwhile
            </div>
        </div>
    </x-card>

    <!-- Month's Activities List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Alle Veranstaltungen im {{ $date->locale('de')->monthName }}</h3>

        @if($events->isEmpty())
            <p class="text-gray-500">Keine Veranstaltungen in diesem Monat.</p>
        @else
            <div class="space-y-4">
                @foreach($events as $event)
                    @php
                        $colorClass = match($event->event_type) {
                            'festival' => 'bg-red-500',
                            'meeting' => 'bg-blue-500',
                            'performance' => 'bg-purple-500',
                            'holiday' => 'bg-gray-500',
                            'sports' => 'bg-green-500',
                            'excursion' => 'bg-yellow-500',
                            default => 'bg-steiner-blue'
                        };
                    @endphp
                    <div class="pb-4 border-b border-gray-100 last:border-0">
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 rounded-full {{ $colorClass }} mt-1 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('school-calendar.show', $event) }}"
                                   class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors block">
                                    {{ $event->title }}
                                </a>
                                <div class="text-sm text-gray-600">
                                    @php
                                        $typeLabel = match($event->event_type) {
                                            'festival' => 'Fest',
                                            'meeting' => 'Treffen',
                                            'performance' => 'Aufführung',
                                            'holiday' => 'Ferien',
                                            'sports' => 'Sport',
                                            'excursion' => 'Ausflug',
                                            default => 'Veranstaltung'
                                        };
                                    @endphp
                                    <span class="text-gray-700 font-medium">{{ $typeLabel }}</span>
                                    - {{ $event->start_date->format('d.m.Y') }}@if($event->end_date && !$event->start_date->isSameDay($event->end_date)) - {{ $event->end_date->format('d.m.Y') }}@endif
                                    @if($event->description)
                                        <br>{{ $event->description }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    </div><!-- End calendar-content -->
</div><!-- End Alpine component -->
@endsection