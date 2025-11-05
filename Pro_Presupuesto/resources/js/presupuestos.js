// Archivo: resources/js/presupuestos.js (CÓDIGO FUENTE)
// Lógica robusta de Sol para la creación y gestión de Presupuestos.

document.addEventListener("DOMContentLoaded", () => {
    // Variable global para almacenar los productos reales cargados desde el backend
    let productos = [];
    let itemIndex = 0; // Usado para asegurar índices únicos en el array items[N] de Laravel

    // Referencias principales del DOM y datos iniciales
    const form = document.getElementById('presupuesto-form');
    if (!form) return;
    
    // IMPORTANTE para la vista 'edit': Carga los detalles existentes del data-attribute del formulario
    // Se asume que en la vista Blade se hace algo como: <form data-details="{{ $presupuesto->detalles->toJson() }}">
    const existingDetails = JSON.parse(form.dataset.details || '[]'); 

    // Elementos del DOM para los cálculos
    const productosContainer = document.getElementById("productos-container");
    const addProductBtn = document.getElementById("add-product");
    const subtotalEl = document.getElementById("subtotal"); // Elemento <span> para mostrar Subtotal
    const totalEl = document.getElementById("total");       // Elemento <span> para mostrar Total
    
    // -----------------------------------------------------------------
    // LÓGICA DE UTILIDAD
    // -----------------------------------------------------------------

    /**
     * Muestra un mensaje temporal al usuario (usa console.warn como fallback).
     * En un entorno real, Sol implementaría aquí un toast o modal.
     */
    function showTemporaryMessage(message, type = 'info') {
        console.warn(`[${type.toUpperCase()}] ${message}`);
        // Implementación visual de un mensaje temporal (ej. un div flotante) iría aquí.
    }


    // -----------------------------------------------------------------
    // LÓGICA DE CARGA DE PRODUCTOS (Día 17 - Sol)
    // -----------------------------------------------------------------

    /**
     * Carga los productos desde el endpoint JSON de Franco.
     */
    async function fetchProductos() {
        try {
            // URL al endpoint creado por Franco en ProductoController
            const response = await fetch('/productos/json', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }); 
                       
            if (!response.ok) {
                throw new Error('Error al cargar los productos. Código: ' + response.status);
            }
            
            const data = await response.json();
            productos = data; // Almacena los productos reales
            console.log('Productos cargados exitosamente:', productos);
            
        } catch (error) {
            console.error('Fallo al obtener los productos:', error);
            // Si falla, los productos quedarán como array vacío, lo que evitará errores de JS.
        }
    }

    // -----------------------------------------------------------------
    // LÓGICA DE CÁLCULO DE TOTALES (MEJORADA CON DESCUENTO)
    // -----------------------------------------------------------------

    /**
     * Recalcula el subtotal de todos los items aplicando cantidad, precio y descuento.
     */
    function recalcularTotales() {
        let subtotalGeneral = 0;
        
        // 1. Recorrer todas las filas de productos
        document.querySelectorAll(".detalle-row").forEach((row) => {
            const cantidadInput = row.querySelector(".input-cantidad");
            const precioInput = row.querySelector(".input-precio");
            const descuentoInput = row.querySelector(".input-descuento");
            const itemSubtotalDisplay = row.querySelector(".item-subtotal-display");
            const itemSubtotalHidden = row.querySelector(".item-subtotal-hidden");

            if (cantidadInput && precioInput && descuentoInput && itemSubtotalDisplay && itemSubtotalHidden) {
                // Aseguramos que los valores sean números, usando 0 si el input está vacío o es inválido
                const cantidad = parseFloat(cantidadInput.value) || 0;
                const precio = parseFloat(precioInput.value) || 0; 
                const descuentoPorcentaje = parseFloat(descuentoInput.value) || 0; 
                
                // Cálculo del Subtotal de la Fila (Aplicando el Descuento)
                const subtotalBruto = cantidad * precio;
                
                // Aplicar factor de descuento, asegurando que no exceda el 100%
                const descuentoFactor = 1 - Math.min(descuentoPorcentaje, 100) / 100;

                const subtotalItem = subtotalBruto * descuentoFactor;
                
                subtotalGeneral += subtotalItem;

                // Actualizar los valores en el DOM (visible) y en el input oculto (para envío)
                itemSubtotalDisplay.textContent = `$${subtotalItem.toFixed(2)}`;
                itemSubtotalHidden.value = subtotalItem.toFixed(2);
            }
        });

        // 2. Actualizar el subtotal y total general en el pie del presupuesto
        if (subtotalEl) {
             subtotalEl.textContent = `$${subtotalGeneral.toFixed(2)}`;
        }
        if (totalEl) {
             // Total general (por ahora igual al subtotal, sin impuestos o descuentos generales)
             totalEl.textContent = `$${subtotalGeneral.toFixed(2)}`;
        }
    }

    /**
     * Genera el HTML para una nueva fila de producto.
     * @param {number} index - Índice de la fila para asegurar nombres de input únicos (items[N]).
     * @param {object} detailData - Datos opcionales para inicializar la fila (ej. en la vista edit).
     * @returns {string} El HTML de la fila.
     */
    function generarFilaHtml(index, detailData = {}) {
        const selectedId = detailData.producto_id || '';
        // Buscar el producto en el array global para obtener el precio base, si no está en detailData
        const productBase = productos.find(p => p.id === parseInt(selectedId));
        
        // Determinar valores iniciales (priorizando detailData si existe)
        const selectedPrecio = detailData.precio_unitario !== undefined 
            ? parseFloat(detailData.precio_unitario).toFixed(2) 
            : (productBase ? parseFloat(productBase.precio_unitario).toFixed(2) : '0.00');

        const cantidadInicial = detailData.cantidad !== undefined ? detailData.cantidad : 1;
        const descuentoInicial = detailData.descuento_aplicado !== undefined ? detailData.descuento_aplicado : 0;
        
        // Si hay datos iniciales, calculamos el subtotal de esa fila para la visualización inicial
        const initialSubtotalBruto = cantidadInicial * parseFloat(selectedPrecio);
        const initialDiscountFactor = 1 - Math.min(parseFloat(descuentoInicial), 100) / 100;
        const initialSubtotal = initialSubtotalBruto * initialDiscountFactor;


        // Se asumen las clases para Tailwind: detalle-row (para buscar la fila), input-cantidad, input-precio, input-descuento
        return `
            <tr class="detalle-row border-b border-gray-100" data-detail-id="${detailData.id || ''}">
                <td class="py-3 px-4 w-1/3">
                    <select name="items[${index}][producto_id]" class="w-full px-3 py-2 border rounded-md producto-select focus:outline-none focus:ring-blue-500">
                        <option value="">Selecciona un producto...</option>
                        ${productos.map(p => {
                            const isSelected = p.id === parseInt(selectedId);
                            // Se añade el precio unitario del producto como data-attribute para facilitar la carga al seleccionar
                            return `<option value="${p.id}" data-price="${parseFloat(p.precio_unitario).toFixed(2)}" ${isSelected ? 'selected' : ''}>${p.nombre} ($${parseFloat(p.precio_unitario).toFixed(2)})</option>`;
                        }).join('')}
                    </select>
                    <!-- Campo oculto para enviar el ID del detalle existente (en modo edición), crucial para el update/delete en Laravel -->
                    ${detailData.id ? `<input type="hidden" name="items[${index}][id]" value="${detailData.id}">` : ''}
                </td>
                <td class="py-3 px-4 w-[10%]">
                    <input type="number" name="items[${index}][cantidad]" class="w-full px-3 py-2 border rounded-md input-cantidad text-right focus:outline-none focus:ring-blue-500" value="${cantidadInicial}" min="1">
                </td>
                <td class="py-3 px-4 w-[15%]">
                    <!-- Campo de descuento (%) -->
                    <input type="number" name="items[${index}][descuento_aplicado]" class="w-full px-3 py-2 border rounded-md input-descuento text-right focus:outline-none focus:ring-blue-500" value="${descuentoInicial}" min="0" max="100" step="0.01">
                </td>
                <td class="py-3 px-4 w-[15%]">
                    <!-- Precio unitario, clave para el cálculo y el envío -->
                    <input type="number" name="items[${index}][precio_unitario]" class="w-full px-3 py-2 border rounded-md input-precio text-right focus:outline-none focus:ring-blue-500" value="${selectedPrecio}" step="0.01">
                </td>
                <td class="py-3 px-4 w-[15%] text-right font-semibold">
                    <span class="item-subtotal-display">$${initialSubtotal.toFixed(2)}</span>
                    <input type="hidden" name="items[${index}][subtotal]" class="item-subtotal-hidden" value="${initialSubtotal.toFixed(2)}">
                </td>
                <td class="py-3 px-4 w-[10%] text-center">
                    <button type="button" class="remove-btn text-red-500 hover:text-red-700 font-bold text-lg leading-none transition duration-150 ease-in-out">&times;</button>
                </td>
            </tr>
        `;
    }

    /**
     * Agrega una nueva fila de producto al contenedor, usando el primer producto como defecto.
     */
    function agregarFilaProducto() {
        // Si no hay productos cargados, evita agregar una fila vacía
        if (productos.length === 0) {
            showTemporaryMessage("No hay productos cargados para agregar. Verifica la conexión con el servidor.", 'error');
            return;
        }
        
        // En modo "creación", agrega un selector vacío para forzar la elección. 
        // En modo "edición" la lógica de carga ya agregó los detalles existentes.
        const newRowHtml = generarFilaHtml(itemIndex++, {}); 
        productosContainer.insertAdjacentHTML('beforeend', newRowHtml);
        recalcularTotales();
    }
    
    /**
     * Carga los detalles existentes del presupuesto (Modo Edición).
     */
    function loadExistingDetails(details) {
        productosContainer.innerHTML = ''; // Asegura que el contenedor esté vacío antes de cargar
        details.forEach(detail => {
            const newRowHtml = generarFilaHtml(itemIndex++, detail);
            productosContainer.insertAdjacentHTML('beforeend', newRowHtml);
        });
        recalcularTotales();
    }

    // -----------------------------------------------------------------
    // LÓGICA DE INICIALIZACIÓN
    // -----------------------------------------------------------------

    /**
     * Carga datos e inicializa listeners.
     */
    async function loadDataAndInitialize() {
        // 1. Cargar productos
        await fetchProductos();

        // 2. Inicializar listeners
        if (productosContainer) {
            
            // Listener para el botón "Agregar Producto"
            if (addProductBtn) {
                addProductBtn.addEventListener("click", agregarFilaProducto);
            }
            
            // 3. Inicializar filas: Cargar existentes (edición) o agregar una nueva (creación)
            if (productos.length > 0) {
                if (existingDetails.length > 0) {
                    loadExistingDetails(existingDetails); // Carga la data para modo edición
                } else if (productosContainer.children.length === 0) {
                    // Solo en vista de creación y si no hay filas pre-renderizadas
                    agregarFilaProducto();
                }
            } else {
                 showTemporaryMessage("No se pudo cargar la lista de productos. Funcionalidad de presupuesto deshabilitada.", 'error');
            }
            
            // 4. DELEGACIÓN DE EVENTOS: Manejar los eventos de input y click en el contenedor padre
            productosContainer.addEventListener('input', (e) => {
                // Ahora también escucha cambios en el input-descuento
                if (e.target.classList.contains('input-cantidad') || 
                    e.target.classList.contains('input-precio') ||
                    e.target.classList.contains('input-descuento')) {
                    recalcularTotales();
                }
            });

            productosContainer.addEventListener('change', (e) => {
                // Revisa si es un cambio en el select del producto
                if (e.target.classList.contains('producto-select')) {
                    const row = e.target.closest('.detalle-row');
                    // Aseguramos que el ID sea numérico (o null si es la opción vacía)
                    const newProductoId = parseInt(e.target.value) || null; 
                    
                    // --- INICIO LÓGICA DE VALIDACIÓN DE DUPLICADOS (Día 18 - Sol) ---
                    let isDuplicated = false;
                    if (newProductoId) {
                        const currentRows = document.querySelectorAll(".detalle-row");
                        let count = 0;
                        currentRows.forEach(currentRow => {
                            const select = currentRow.querySelector(".producto-select");
                            // Verifica si el valor no está vacío y es el mismo ID
                            if (select.value && parseInt(select.value) === newProductoId) {
                                count++;
                            }
                        });

                        // Si se encuentra más de una vez (la fila actual + otra anterior)
                        if (count > 1) {
                            isDuplicated = true;
                        }
                    }

                    if (isDuplicated) {
                        showTemporaryMessage("🚨 El producto ya fue agregado al presupuesto. Por favor, selecciona otro.", 'error');
                        // Resetear el select de la fila actual a vacío
                        e.target.value = ""; 
                    }
                    // --- FIN LÓGICA DE VALIDACIÓN DE DUPLICADOS ---

                    const productoSeleccionado = productos.find(p => p.id === newProductoId);
                    
                    // Actualiza el precio unitario del input de la fila y resetea cantidad/descuento
                    if (productoSeleccionado && row) {
                        const precioInput = row.querySelector(".input-precio");
                        const descuentoInput = row.querySelector(".input-descuento");
                        const cantidadInput = row.querySelector(".input-cantidad");

                        if (precioInput) {
                            // Cargar el precio unitario del producto seleccionado
                            precioInput.value = parseFloat(productoSeleccionado.precio_unitario).toFixed(2);
                        }
                        // Resetea cantidad y descuento al cambiar de producto
                        if (cantidadInput) cantidadInput.value = 1;
                        if (descuentoInput) descuentoInput.value = 0;
                        
                    } else if (row) {
                        // Si selecciona la opción vacía (o si el duplicado forzó el reset), limpiar campos
                        const precioInput = row.querySelector(".input-precio");
                        const descuentoInput = row.querySelector(".input-descuento");
                        const cantidadInput = row.querySelector(".input-cantidad");
                        
                        if (precioInput) precioInput.value = '0.00';
                        if (cantidadInput) cantidadInput.value = 1;
                        if (descuentoInput) descuentoInput.value = 0;
                    }
                    recalcularTotales();
                }
            });

            productosContainer.addEventListener('click', (e) => {
                // Revisa si es un click en el botón de eliminar
                if (e.target.closest('.remove-btn')) {
                    const row = e.target.closest('.detalle-row');
                    if (row) {
                        // Si queda solo una fila, la limpiamos, no la eliminamos
                        if (document.querySelectorAll(".detalle-row").length > 1) {
                            row.remove();
                            recalcularTotales();
                        } else {
                            // Limpiar la fila si es la única (comportamiento de reset)
                            const select = row.querySelector(".producto-select");
                            const cantidadInput = row.querySelector(".input-cantidad");
                            const precioInput = row.querySelector(".input-precio");
                            const descuentoInput = row.querySelector(".input-descuento");
                            
                            if (select) select.value = "";
                            if (cantidadInput) cantidadInput.value = 1;
                            if (precioInput) precioInput.value = '0.00';
                            if (descuentoInput) descuentoInput.value = 0;
                            recalcularTotales();
                        }
                    }
                }
            });
        }
    }
    
    // Iniciar la aplicación
    loadDataAndInitialize();
    
    // -----------------------------------------------------------------
    // LÓGICA DE CAMBIO DE ESTADO DE PRESUPUESTOS (Tabla Listado) - Se mantiene
    // -----------------------------------------------------------------
    
    const statusButtons = document.querySelectorAll('.btn-status');
    // NOTA: Se asegura de que el token CSRF se obtenga correctamente
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;


    if (statusButtons.length > 0 && csrfToken) { // Asegura que el token esté disponible
        statusButtons.forEach(button => {
            button.addEventListener('click', async (event) => {
                const id = button.dataset.id;
                
                try {
                    const response = await fetch(`/presupuestos/${id}/estado`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken, 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        const newStatus = data.new_status;
                        
                        // 1. Actualizar data attributes y texto
                        button.dataset.status = newStatus;
                        button.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        
                        // 2. Actualizar las clases de Tailwind (estilo visual)
                        const statusClasses = {
                            'facturado': ['bg-green-100', 'text-green-800'],
                            'pendiente': ['bg-red-100', 'text-red-800'],
                            // Se puede añadir 'aceptado', 'rechazado' si existen
                        };

                        // Limpiar clases existentes
                        button.classList.remove('bg-red-100', 'text-red-800', 'bg-green-100', 'text-green-800', 'bg-yellow-100', 'text-yellow-800');
                        
                        // Aplicar nuevas clases
                        if (statusClasses[newStatus]) {
                            button.classList.add(...statusClasses[newStatus]);
                        } else {
                            // Estado genérico o no reconocido
                            button.classList.add('bg-yellow-100', 'text-yellow-800');
                        }

                    } else {
                        console.error('Error al actualizar el estado. Estado de respuesta:', response.status);
                    }
                } catch (error) {
                    console.error('Hubo un error en la petición:', error);
                }
            });
        });
    }
});
