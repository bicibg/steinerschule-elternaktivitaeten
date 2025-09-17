<x-card noPadding="true">
    <!-- Month Navigation -->
    <x-calendar.navigation :date="$date" route="school-calendar.index" />

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