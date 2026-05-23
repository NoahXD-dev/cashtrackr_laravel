@props(['type' => 'success', 'message' => ''])

@php
    $colors = [
        'success' => 'border-green-700 bg-green-100 text-green-700',
        'error' => 'border-red-700 bg-red-100 text-red-700'
    ];

    $color = $colors[$type] ?? $colors['success'];

@endphp

@if($message)
    <p class="my-10 text-center border-l-8 text-sm py-3 font-bold uppercase {{ $color }}">
        {{ $message }}
    </p>
@endif