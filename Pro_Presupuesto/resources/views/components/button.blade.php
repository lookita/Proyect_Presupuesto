@props(['type' => 'submit', 'variant' => 'primary'])

@php
    // Definición de clases según la variante
    $baseClasses = 'px-4 py-2 rounded-lg font-semibold focus:outline-none transition duration-150 shadow-md';
    
    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-4 focus:ring-green-300',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-4 focus:ring-red-300',
        'secondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-4 focus:ring-gray-400',
    ];
    
    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<button 
    type="{{ $type }}" 
    {{-- Las clases fusionan las clases base, la clase de la variante y cualquier clase extra --}}
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClass]) }}
>
    {{ $slot }}
</button>