<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Crear Nuevo Cliente</h1>
        
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</body>
</html>

<!-- @csrf: Esta directiva de Laravel genera un token de seguridad para proteger el formulario contra ataques de tipo 
Cross-Site Request Forgery (CSRF). Es una práctica de seguridad fundamental que debes incluir en todos los formularios 
que envíen datos.
@method('POST'): En este caso, el atributo method del formulario ya es POST, por lo que la directiva @method no es 
estrictamente necesaria, pero se usa comúnmente en formularios de Laravel para cambiar el método HTTP a otros verbos 
como PUT, PATCH o DELETE (que no son compatibles de forma nativa con los navegadores). Para una creación simple, 
method="POST" es suficiente.
Campos del Formulario: Se incluyen tres campos de entrada (<input>) con los atributos id y name correspondientes a
nombre y email. Es crucial que el atributo name coincida con los nombres de los campos que esperará tu 
controlador de Laravel.
Clases de Tailwind: Se han utilizado varias clases de Tailwind para dar estilo al formulario:
bg-gray-100: Color de fondo del cuerpo de la página.
max-w-md mx-auto: Centra el contenedor del formulario horizontalmente y le da un ancho máximo.
p-6 rounded-lg shadow-md: Añade padding, esquinas redondeadas y una sombra al contenedor.
w-full px-3 py-2 border rounded-md: Estas clases dan un estilo limpio a los campos de entrada, haciéndolos ocupar 
todo el ancho disponible, con bordes y relleno.
bg-blue-600 text-white: Estilo para el botón de envío, dándole un color de fondo azul y texto blanco.
action="{{ route('clientes.store') }}": Esta directiva de Laravel genera automáticamente la URL correcta para la 
ruta con nombre clientes.store, que debería estar definida en tu archivo de rutas (web.php). -->