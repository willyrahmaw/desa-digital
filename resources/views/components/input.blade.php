@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
])

<div class="w-full">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700 mb-2">{{ $label }}</label>
    @endif
    <input 
        id="{{ $name }}" 
        name="{{ $name }}" 
        type="{{ $type }}" 
        value="{{ $value }}"
        {{ $attributes->merge(['class' => 'block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 sm:text-sm']) }}
    >
</div>
