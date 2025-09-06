Documentación Día 5: Modelos y Relaciones

Objetivo: Crear los modelos Producto, Presupuesto y PresupuestoDetalle, y definir relaciones entre ellos y con Cliente.

Modelos creados:
Producto: nombre, precio	hasMany(PresupuestoDetalle)

Presupuesto: cliente_id, fecha, estado	belongsTo(Cliente) + hasMany(PresupuestoDetalle)

PresupuestoDetalle: presupuesto_id, producto_id, cantidad, subtotal	belongsTo(Presupuesto) + belongsTo(Producto)




Documentación Día 6: ProductoController + Validaciones

Objetivo: Desarrollar ProductoController con lógica CRUD y aplicar validaciones numeric|min:0.01|unique.

Controlador creado: ProductoController.php
Método	Función técnica
index()	Muestra todos los productos
create()	Muestra el formulario de creación
store()	Valida y guarda un nuevo producto
edit()	Muestra el formulario de edición
update()	Valida y actualiza un producto existente
destroy()	Elimina un producto
🧾 Validaciones aplicadas
Campo	Reglas aplicadas
nombre	required, string, unique:productos,nombre
precio	required, numeric, min:0.01
Implementadas en:

ProductoStoreRequest.php

ProductoUpdateRequest.php

📁 Organización de archivos
Requests ubicados correctamente en app/Http/Requests/

Controlador en app/Http/Controllers/

Modelo en app/Models/Producto.php
