@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'error' => null
])

<div class="mb-4">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent' . ($errors->has($name) ? ' border-red-500' : '')]) }}
    >

    @error($name)
    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @else
        @if($error)
        <p class="text-sm text-red-600 mt-1">{{ $error }}</p>
        @endif
    @enderror
</div>