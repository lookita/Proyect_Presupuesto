document.addEventListener("DOMContentLoaded", () => {
    // Lógica para el formulario de Creación de Presupuestos

    // Verificamos si los elementos del formulario existen en la página
    const productosContainer = document.getElementById("productos-container");
    if (productosContainer) {
        const addProductBtn = document.getElementById("add-product");
        const subtotalEl = document.getElementById("subtotal");
        const totalEl = document.getElementById("total");

        // Datos de productos de ejemplo (en el futuro, esto vendrá de una API)
        const productos = [
            { id: 1, nombre: "Servicio de Diseño Gráfico", precio: 1500.0 },
            { id: 2, nombre: "Desarrollo Web (por hora)", precio: 50.0 },
            { id: 3, nombre: "Licencia de Software (anual)", precio: 300.0 },
            { id: 4, nombre: "Consultoría SEO", precio: 800.0 },
        ];

    // Función para recalcular el subtotal y el total
        function recalcularTotales() {
            let subtotal = 0;
            document.querySelectorAll(".producto-item").forEach((row) => {
                const productoId = parseInt(
                    row.querySelector(".producto-select").value
                );
                const cantidad =
                    parseInt(row.querySelector(".cantidad-input").value) || 0;
                const producto = productos.find((p) => p.id === productoId);

                if (producto) {
                    const precioUnitario = producto.precio;
                    const itemSubtotal = precioUnitario * cantidad;
                    subtotal += itemSubtotal;
                    row.querySelector(
                        ".item-subtotal"
                    ).textContent = `$${itemSubtotal.toFixed(2)}`;
                }
            });

            subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
        totalEl.textContent = `$${subtotal.toFixed(2)}`; // En este proyecto, total es igual a subtotal
        }

    // Función para agregar una nueva fila de producto
        function agregarFilaProducto() {
            const row = document.createElement("tr");
            row.classList.add("producto-item", "border-b", "border-gray-200");

            row.innerHTML = `
                <td class="py-2 px-4">
                    <select name="items[][id]" class="w-full px-3 py-2 border rounded-md producto-select focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona un producto...</option>
                        ${productos
                            .map((p) => `<option value="${p.id}">${p.nombre}</option>`)
                            .join("")}
                    </select>
                </td>
                <td class="py-2 px-4">
                    <input type="number" name="items[][cantidad]" class="w-full px-3 py-2 border rounded-md cantidad-input text-right focus:outline-none focus:ring-2 focus:ring-blue-500" value="1" min="1">
                </td>
                <td class="py-2 px-4 text-right">
                    <span class="precio-unitario text-gray-600">$0.00</span>
                </td>
                <td class="py-2 px-4 text-right font-medium">
                    <span class="item-subtotal">$0.00</span>
                </td>
                <td class="py-2 px-4 text-center">
                    <button type="button" class="remove-btn text-red-500 hover:text-red-700 font-bold text-lg leading-none">&times;</button>
                </td>
            `;

            productosContainer.appendChild(row);

        // Agregamos los event listeners a los nuevos elementos
            const select = row.querySelector(".producto-select");
            const input = row.querySelector(".cantidad-input");
            const removeBtn = row.querySelector(".remove-btn");

            select.addEventListener("change", (e) => {
                const productoSeleccionado = productos.find(
                    (p) => p.id == e.target.value
                );
                if (productoSeleccionado) {
                    row.querySelector(
                        ".precio-unitario"
                    ).textContent = `$${productoSeleccionado.precio.toFixed(2)}`;
                } else {
                    row.querySelector(".precio-unitario").textContent = `$0.00`;
                }
                recalcularTotales();
            });

            input.addEventListener("input", recalcularTotales);
            removeBtn.addEventListener("click", () => {
                row.remove();
                recalcularTotales();
            });

            recalcularTotales(); // Recalculamos el total al agregar una nueva fila
        }

    // Listener para el botón "Agregar Producto"
        addProductBtn.addEventListener("click", agregarFilaProducto);
        agregarFilaProducto();
    }

    // Lógica para el botón de cambio de estado en la tabla

    // Verificamos si los botones de estado existen en la página
    const statusButtons = document.querySelectorAll('.btn-status');
    if (statusButtons.length > 0) {
        statusButtons.forEach(button => {
            button.addEventListener('click', async (event) => {
                const id = button.dataset.id;
                const currentStatus = button.dataset.status;

                try {
                    const response = await fetch(`/presupuestos/${id}/estado`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        button.dataset.status = data.new_status;
                        button.textContent = data.new_status;
                        
                        if (data.new_status === 'facturado') {
                            button.classList.remove('bg-red-100', 'text-red-800');
                            button.classList.add('bg-green-100', 'text-green-800');
                        } else {
                            button.classList.remove('bg-green-100', 'text-green-800');
                            button.classList.add('bg-red-100', 'text-red-800');
                        }
                    } else {
                        console.error('Error al actualizar el estado.');
                    }
                } catch (error) {
                    console.error('Hubo un error en la petición:', error);
                }
            });
        });
    }
});