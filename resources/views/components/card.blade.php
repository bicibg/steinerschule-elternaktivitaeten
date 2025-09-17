@props(['hover' => false, 'compact' => false, 'noPadding' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200 ' . ($hover ? 'hover:shadow-md transition-shadow duration-200' : '')]) }}>
    @if(isset($header))
    <div class="border-b border-gray-200 px-6 py-4">
        {{ $header }}
    </div>
    @endif

    @if(!$noPadding)
    <div class="{{ $compact ? 'p-4' : 'p-6' }}">
        {{ $slot }}
    </div>
    @else
        {{ $slot }}
    @endif

    @if(isset($footer))
    <div class="border-t border-gray-200 px-6 py-4">
        {{ $footer }}
    </div>
    @endif
</div>