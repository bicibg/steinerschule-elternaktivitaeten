@props([
    'label' => null,
    'name',
    'value' => null,
    'required' => false,
    'options' => [],
    'placeholder' => null
])

<div class="mb-4">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent' . ($errors->has($name) ? ' border-red-500' : '')]) }}
    >
        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
            {{ $optionLabel }}
        </option>
        @endforeach
    </select>

    @error($name)
    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>