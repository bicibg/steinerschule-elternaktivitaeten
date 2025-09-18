@extends('layouts.app')

@section('title', 'Schichtkalender')

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
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Schichtkalender</h1>
        <p class="text-gray-600">
            Hier sehen Sie alle <strong>Schichten und Aktivitätstermine</strong> der Elterngruppen.
            Neue Helfer sind immer willkommen - klicken Sie auf einen Eintrag, um sich anzumelden.
        </p>
    </div>

    <x-info-box type="help">
        <strong>Mitmachen erwünscht!</strong> Dieser Kalender zeigt alle Termine der Elternaktivitäten. Die meisten Gruppen freuen sich über neue Unterstützer.
        <br>
        <span class="text-xs">Dringende Helfergesuche finden Sie auf der <a href="{{ route('bulletin.index') }}" class="underline text-steiner-blue hover:text-steiner-dark">Pinnwand</a></span>
    </x-info-box>

    @php
        // Collect all unique items for legend
        $legendItems = [];
        foreach ($itemsByDate as $items) {
            foreach ($items as $item) {
                $key = $item['activity']->id . '::' . ($item['type'] === 'shift' ? $item['title'] : $item['type']);
                if (!isset($legendItems[$key])) {
                    $legendItems[$key] = [
                        'activity' => $item['activity']->title,
                        'title' => $item['title'],
                        'type' => $item['type'],
                        'color' => $item['color'],
                        'note' => $item['note'] ?? null,
                    ];
                }
            }
        }
    @endphp

    <!-- Calendar Container -->
    <div id="calendar-content">
    <x-card noPadding="true">
        <!-- Month Navigation -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <button @click="navigateToMonth('{{ route('calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}')"
                   class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <h2 class="text-xl font-semibold text-gray-800">
                {{ $date->locale('de')->monthName . ' ' . $date->year }}
            </h2>

            <button @click="navigateToMonth('{{ route('calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}')"
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
                <!-- Calendar Days -->
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
                        $dayItems = $itemsByDate->get($dateKey, collect());
                    @endphp

                    <div class="bg-white h-[120px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
                        <div class="h-full flex flex-col">
                            <div class="font-medium text-sm px-1 pt-0.5 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                {{ $currentDate->day }}
                            </div>

                            <div class="flex-1 overflow-hidden">
                                @foreach($dayItems as $item)
                                    @php
                                        $isSpanning = ($item['type'] === 'production' || $item['type'] === 'flexible') && (isset($item['is_start']) || isset($item['is_middle']) || isset($item['is_end']));
                                        $roundedClass = '';
                                        if ($isSpanning) {
                                            if (isset($item['is_start']) && isset($item['is_end'])) {
                                                $roundedClass = 'rounded';
                                            } elseif (isset($item['is_start'])) {
                                                $roundedClass = 'rounded-l';
                                            } elseif (isset($item['is_end'])) {
                                                $roundedClass = 'rounded-r';
                                            }
                                        } else {
                                            $roundedClass = 'rounded';
                                        }
                                    @endphp
                                    <a href="{{ route('bulletin.show', $item['activity']->slug) }}"
                                       class="block text-xs px-0.5 mb-px {{ $roundedClass }} {{ $item['color'] }} text-white hover:opacity-75 transition-opacity truncate"
                                       title="{{ $item['activity']->title }}{{ isset($item['shift']) ? ': ' . $item['title'] . ' (' . $item['shift']->capacity_display . ' Helfer)' : '' }}{{ isset($item['date_range']) ? ' (' . $item['date_range'] . ')' : '' }}">
                                        @if($item['type'] === 'shift')
                                            {{ $item['title'] }}
                                        @elseif($isSpanning)
                                            @if(isset($item['is_start']))
                                                {{ $item['activity']->title }}
                                            @else
                                                &nbsp;
                                            @endif
                                        @elseif($item['type'] === 'meeting')
                                            {{ $item['activity']->title }}
                                        @else
                                            {{ $item['title'] }}
                                        @endif
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
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Alle Einträge im {{ $date->locale('de')->monthName }}</h3>

        @if($itemsByDate->isEmpty() && (!isset($productionActivities) || $productionActivities->isEmpty()))
            <p class="text-gray-500">Keine Einträge in diesem Monat.</p>
        @else
            <div class="space-y-4">
                @php
                    // Group spanning activities (production and flexible) separately
                    $displayedSpanning = collect();
                    $spanningActivities = collect();
                    $seenActivities = collect();

                    // Group production and flexible activities by their activity ID
                    foreach($itemsByDate as $dateKey => $items) {
                        foreach($items as $item) {
                            if (($item['type'] === 'production' || $item['type'] === 'flexible')) {
                                // Only add once per activity
                                if (!$seenActivities->contains($item['activity']->id)) {
                                    $seenActivities->push($item['activity']->id);

                                    // Find the first and last date for this activity in the month
                                    $activityDates = collect();
                                    foreach($itemsByDate as $dk => $dayItems) {
                                        foreach($dayItems as $di) {
                                            if ($di['activity']->id === $item['activity']->id) {
                                                $activityDates->push(\Carbon\Carbon::parse($dk));
                                            }
                                        }
                                    }

                                    if ($activityDates->isNotEmpty()) {
                                        // Use the actual activity dates, not just what's visible in this month
                                        $actualStart = $item['activity']->start_at ?? $item['activity']->flexible_start;
                                        $actualEnd = $item['activity']->end_at ?? $item['activity']->flexible_end;

                                        $dateRangeStr = '';
                                        if ($actualStart && $actualEnd) {
                                            // Always show year for clarity, with spacing around dash
                                            $dateRangeStr = $actualStart->format('d.m.Y') . ' - ' . $actualEnd->format('d.m.Y');
                                        } elseif ($actualStart) {
                                            $dateRangeStr = $actualStart->format('d.m.Y');
                                        }

                                        $spanningActivities->push([
                                            'activity' => $item['activity'],
                                            'type' => $item['type'],
                                            'color' => $item['color'],
                                            'date_range' => $dateRangeStr,
                                            'note' => $item['note'] ?? null,
                                        ]);
                                        $displayedSpanning->push($item['activity']->id);
                                    }
                                }
                            }
                        }
                    }
                @endphp

                @foreach($spanningActivities as $spanning)
                    <div class="pb-4 border-b border-gray-100 last:border-0">
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 rounded-full {{ $spanning['color'] }} mt-1 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('bulletin.show', $spanning['activity']->slug) }}"
                                   class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors block">
                                    {{ $spanning['activity']->title }}
                                </a>
                                <div class="text-sm text-gray-600">
                                    @if($spanning['type'] === 'production')
                                        <span class="text-yellow-700 font-medium">Produktion</span>
                                    @elseif($spanning['type'] === 'flexible')
                                        <span class="text-green-700 font-medium">Flexible Hilfe</span>
                                    @endif
                                    @if($spanning['date_range'])
                                        - {{ $spanning['date_range'] }}
                                    @endif
                                    @if($spanning['activity']->participation_note)
                                        <br>{{ $spanning['activity']->participation_note }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach($itemsByDate->sortKeys() as $dateKey => $items)
                    @php
                        $itemDate = \Carbon\Carbon::parse($dateKey);
                        // Filter out spanning activities as they're shown separately
                        $filteredItems = $items->filter(function($item) use ($displayedSpanning) {
                            if (($item['type'] === 'production' || $item['type'] === 'flexible')) {
                                return !$displayedSpanning->contains($item['activity']->id);
                            }
                            return true;
                        });
                    @endphp
                    @if($filteredItems->isNotEmpty())
                        <div class="pb-4 border-b border-gray-100 last:border-0">
                            <div class="font-medium text-gray-800 mb-3">
                                {{ $itemDate->locale('de')->dayName }}, {{ $itemDate->format('d.m.Y') }}
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($filteredItems as $item)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-3 h-3 rounded-full {{ $item['color'] }} mt-1 flex-shrink-0"></div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('bulletin.show', $item['activity']->slug) }}"
                                               class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors block">
                                                {{ $item['activity']->title }}
                                            </a>
                                            <div class="text-sm text-gray-600">
                                                @if($item['type'] === 'shift')
                                                    <strong>{{ $item['title'] }}</strong>
                                                    @if(isset($item['shift']->time))
                                                        - {{ $item['shift']->time }}
                                                    @endif
                                                    @if(isset($item['shift']->needed))
                                                        <span class="text-xs">
                                                            ({{ $item['shift']->capacity_display }} Helfer)
                                                        </span>
                                                    @elseif(isset($item['shift']->flexible_capacity) && $item['shift']->flexible_capacity)
                                                        <span class="text-xs text-green-600">Flexible Teilnahme</span>
                                                    @endif
                                                @elseif($item['type'] === 'meeting')
                                                    <span class="text-blue-700 font-medium">Regelmässiges Treffen</span>
                                                    @if($item['note'])
                                                        - {{ $item['note'] }}
                                                    @endif
                                                @elseif($item['type'] === 'flexible')
                                                    <span class="text-green-700 font-medium">Flexible Hilfe</span>
                                                    @if($item['note'])
                                                        - {{ $item['note'] }}
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
    </div><!-- End calendar-content -->
</div><!-- End Alpine component -->
@endsection