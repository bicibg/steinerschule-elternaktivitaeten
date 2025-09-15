@props([
    'date',
    'isCurrentMonth' => true,
    'isToday' => false
])

<div class="bg-white h-[120px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
    <div class="h-full flex flex-col">
        <div class="font-medium text-sm px-1 pt-0.5 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
            {{ $date->day }}
        </div>

        <div class="flex-1 overflow-hidden">
            {{ $slot }}
        </div>
    </div>
</div>