@props(['type' => 'text', 'name', 'value' => '', 'placeholder' => ''])

<input 
    type="{{ $type }}" 
    name="{{ $name }}" 
    id="{{ $name }}" 
    value="{{ old($name, $value) }}" 
    placeholder="{{ $placeholder }}"
    {{-- $attributes->merge permite añadir clases o atributos adicionales desde el uso del componente --}}
    {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 shadow-sm']) }}
>

{{-- Manejo de errores de validación estándar --}}
@error($name) 
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
@enderror