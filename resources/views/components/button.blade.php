@props([
    'variant' => 'primary', // primary, secondary, danger, success
])

@php
    $classes = 'inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded transition-colors duration-150 focus:outline-none ';
    switch ($variant) {
        case 'secondary':
            $classes .= 'bg-slate-200 text-slate-700 hover:bg-slate-300';
            break;
        case 'danger':
            $classes .= 'bg-rose-600 text-white hover:bg-rose-700';
            break;
        case 'success':
            $classes .= 'bg-emerald-600 text-white hover:bg-emerald-700';
            break;
        case 'primary':
        default:
            $classes .= 'bg-indigo-600 text-white hover:bg-indigo-700';
            break;
    }
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
