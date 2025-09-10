@extends('layouts.app')

@section('title', 'Crear Nuevo Producto')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Crear Nuevo Producto</h1>
        
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre</label>
                <input type="text" id="nombre" name="nombre" 
                       value="{{ old('nombre') }}" 
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror" 
                       required maxlength="100">
                @error('nombre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="codigo" class="block text-gray-700 font-bold mb-2">Código</label>
                <input type="text" id="codigo" name="codigo" 
                       value="{{ old('codigo') }}" 
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('codigo') border-red-500 @enderror" 
                       required maxlength="50">
                @error('codigo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="precio" class="block text-gray-700 font-bold mb-2">Precio</label>
                <input type="number" id="precio" name="precio" 
                       value="{{ old('precio') }}" 
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('precio') border-red-500 @enderror" 
                       step="0.01" required min="0.01">
                @error('precio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
@endsection