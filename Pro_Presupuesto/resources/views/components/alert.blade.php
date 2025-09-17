@props(['type' => 'info', 'message'])

@php
    $colors = [
        'success' => 'green',
        'error' => 'red',
        'info' => 'blue',
        'warning' => 'yellow',
    ];
    $color = $colors[$type] ?? 'blue';
@endphp

<div class="p-4 mb-4 text-sm text-{{ $color }}-700 bg-{{ $color }}-100 rounded-lg">
    {{ $message }}
</div>
