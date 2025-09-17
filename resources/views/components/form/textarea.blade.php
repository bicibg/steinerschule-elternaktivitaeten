@props([
    'label' => null,
    'name',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'rows' => 3,
    'maxlength' => null
])

<div class="mb-4">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent' . ($errors->has($name) ? ' border-red-500' : '')]) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>