<?php
//agregue los enlaces y asegura que la navegacion funcione desde el menu
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">Clientes</x-nav-link>
    <x-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">Productos</x-nav-link>
    <x-nav-link :href="route('presupuestos.index')" :active="request()->routeIs('presupuestos.*')">Presupuestos</x-nav-link>
</div>

//roles y permisos para usuarios y productos
@if(Auth::user()->role == 'admin')
    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">Usuarios</x-nav-link>
@endif

@if(Auth::user()->role == 'admin')
    <a href="{{ route('productos.create') }}" class="bg-green-500 text-white px-3 py-2 rounded">Nuevo Producto</a>
@endif

