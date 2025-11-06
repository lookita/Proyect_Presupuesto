// Archivo: resources/js/presupuestos.js
// Gestión completa de presupuestos (creación, edición, cálculo de totales, cambio de estado)
// Refactorizado para encapsular lógica en un objeto PresupuestoApp

document.addEventListener("DOMContentLoaded", () => {
    const PresupuestoApp = {
        productos: [],
        itemIndex: 0,
        form: null,
        productosContainer: null,
        addProductBtn: null,
        subtotalEl: null,
        totalEl: null,
        existingDetails: [],

        init() {
            this.cacheDom();
            if (!this.form) return;

            this.existingDetails = JSON.parse(
                this.form.dataset.details || "[]"
            );

            this.fetchProductos().then(() => {
                this.bindEvents();
                if (this.existingDetails.length > 0) {
                    this.loadExistingDetails(this.existingDetails);
                } else if (this.productosContainer.children.length === 0) {
                    this.agregarFilaProducto();
                }
            });

            this.initStatusButtons();
        },

        cacheDom() {
            this.form = document.getElementById("presupuesto-form");
            this.productosContainer = document.getElementById(
                "productos-container"
            );
            this.addProductBtn = document.getElementById("add-product");
            this.subtotalEl = document.getElementById("subtotal");
            this.totalEl = document.getElementById("total");
        },

        showTemporaryMessage(message, type = "info") {
            console.warn(`[${type.toUpperCase()}] ${message}`);
            // Aquí se podría integrar un toast visual
        },

        async fetchProductos() {
            try {
                const response = await fetch("/productos/json", {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                });

                if (!response.ok)
                    throw new Error(
                        "Error al cargar los productos. Código: " +
                            response.status
                    );

                this.productos = await response.json();
                console.log("Productos cargados:", this.productos);
            } catch (error) {
                console.error("Fallo al obtener los productos:", error);
                this.productos = [];
            }
        },

        // ✅ FUNCIÓN AUXILIAR: Obtener precio del producto (compatible con 'precio' o 'precio_unitario')
        getPrecioProducto(producto) {
            if (!producto) return 0;
            // Intentar ambos campos para máxima compatibilidad
            return parseFloat(producto.precio_unitario || producto.precio || 0);
        },

        recalcularTotales() {
            let subtotalBrutoGeneral = 0; // suma de todos los productos sin descuentos
            let totalNetoGeneral = 0; // suma de todos los productos con descuento aplicado

            document.querySelectorAll(".detalle-row").forEach((row) => {
                const cantidadInput = row.querySelector(".input-cantidad");
                const precioInput = row.querySelector(".input-precio");
                const descuentoInput = row.querySelector(".input-descuento");
                const itemSubtotalDisplay = row.querySelector(
                    ".item-subtotal-display"
                );
                const itemSubtotalHidden = row.querySelector(
                    ".item-subtotal-hidden"
                );

                if (
                    cantidadInput &&
                    precioInput &&
                    descuentoInput &&
                    itemSubtotalDisplay &&
                    itemSubtotalHidden
                ) {
                    const cantidad = parseFloat(cantidadInput.value) || 0;
                    const precio = parseFloat(precioInput.value) || 0;
                    const descuento = parseFloat(descuentoInput.value) || 0;

                    const subtotalBruto = cantidad * precio; // subtotal bruto de la fila
                    const subtotalNeto =
                        subtotalBruto * (1 - Math.min(descuento, 100) / 100); // subtotal neto con descuento

                    subtotalBrutoGeneral += subtotalBruto; // acumulamos subtotal bruto
                    totalNetoGeneral += subtotalNeto; // acumulamos subtotal neto

                    // Actualizamos la fila para mostrar subtotal neto (con descuento)
                    itemSubtotalDisplay.textContent = `$${subtotalNeto.toFixed(
                        2
                    )}`;
                    itemSubtotalHidden.value = subtotalNeto.toFixed(2);
                }
            });

            // Actualizamos los spans visibles
            if (this.subtotalEl)
                this.subtotalEl.textContent = `$${subtotalBrutoGeneral.toFixed(
                    2
                )}`;
            if (this.totalEl)
                this.totalEl.textContent = `$${totalNetoGeneral.toFixed(2)}`;

            // Actualizamos los hidden inputs para enviar al backend
            const hiddenSubtotal = document.getElementById("hidden-subtotal");
            const hiddenTotal = document.getElementById("hidden-total");
            if (hiddenSubtotal)
                hiddenSubtotal.value = subtotalBrutoGeneral.toFixed(2);
            if (hiddenTotal) hiddenTotal.value = totalNetoGeneral.toFixed(2);
        },

        generarFilaHtml(index, detailData = {}) {
            const selectedId = detailData.producto_id || "";
            const productBase = this.productos.find(
                (p) => p.id === parseInt(selectedId)
            );

            // ✅ CORRECCIÓN: Usar la función auxiliar para obtener el precio
            const selectedPrecio =
                detailData.precio_unitario !== undefined
                    ? parseFloat(detailData.precio_unitario).toFixed(2)
                    : productBase
                    ? this.getPrecioProducto(productBase).toFixed(2)
                    : "0.00";

            const cantidadInicial =
                detailData.cantidad !== undefined ? detailData.cantidad : 1;
            const descuentoInicial =
                detailData.descuento_aplicado !== undefined
                    ? detailData.descuento_aplicado
                    : 0;

            const initialSubtotalBruto =
                cantidadInicial * parseFloat(selectedPrecio);
            const initialDiscountFactor =
                1 - Math.min(parseFloat(descuentoInicial), 100) / 100;
            const initialSubtotal =
                initialSubtotalBruto * initialDiscountFactor;

            return `
                <tr class="detalle-row border-b border-gray-100" data-detail-id="${
                    detailData.id || ""
                }">
                    <td class="py-3 px-4 w-1/3">
                        <select name="items[${index}][producto_id]" class="w-full px-3 py-2 border rounded-md producto-select focus:outline-none focus:ring-blue-500">
                            <option value="">Selecciona un producto...</option>
                            ${this.productos
                                .map((p) => {
                                    const isSelected =
                                        p.id === parseInt(selectedId);
                                    const precio = this.getPrecioProducto(p);
                                    return `<option value="${
                                        p.id
                                    }" data-price="${precio.toFixed(2)}" ${
                                        isSelected ? "selected" : ""
                                    }>${p.nombre} ($${precio.toFixed(
                                        2
                                    )})</option>`;
                                })
                                .join("")}
                        </select>
                        ${
                            detailData.id
                                ? `<input type="hidden" name="items[${index}][id]" value="${detailData.id}">`
                                : ""
                        }
                    </td>
                    <td class="py-3 px-4 w-[10%]">
                        <input type="number" name="items[${index}][cantidad]" class="w-full px-3 py-2 border rounded-md input-cantidad text-right focus:outline-none focus:ring-blue-500" value="${cantidadInicial}" min="1">
                    </td>
                    <td class="py-3 px-4 w-[15%]">
                        <input type="number" name="items[${index}][descuento_aplicado]" class="w-full px-3 py-2 border rounded-md input-descuento text-right focus:outline-none focus:ring-blue-500" value="${descuentoInicial}" min="0" max="100" step="0.01">
                    </td>
                    <td class="py-3 px-4 w-[15%]">
                        <input type="number" name="items[${index}][precio_unitario]" class="w-full px-3 py-2 border rounded-md input-precio text-right focus:outline-none focus:ring-blue-500" value="${selectedPrecio}" step="0.01">
                    </td>
                    <td class="py-3 px-4 w-[15%] text-right font-semibold">
                        <span class="item-subtotal-display">$${initialSubtotal.toFixed(
                            2
                        )}</span>
                        <input type="hidden" name="items[${index}][subtotal]" class="item-subtotal-hidden" value="${initialSubtotal.toFixed(
                2
            )}">
                    </td>
                    <td class="py-3 px-4 w-[10%] text-center">
                        <button type="button" class="remove-btn text-red-500 hover:text-red-700 font-bold text-lg leading-none transition duration-150 ease-in-out">&times;</button>
                    </td>
                </tr>
            `;
        },

        agregarFilaProducto() {
            if (this.productos.length === 0) {
                this.showTemporaryMessage(
                    "No hay productos cargados para agregar.",
                    "error"
                );
                return;
            }
            const newRowHtml = this.generarFilaHtml(this.itemIndex++);
            this.productosContainer.insertAdjacentHTML("beforeend", newRowHtml);
            this.recalcularTotales();
        },

        loadExistingDetails(details) {
            this.productosContainer.innerHTML = "";
            details.forEach((detail) => {
                const newRowHtml = this.generarFilaHtml(
                    this.itemIndex++,
                    detail
                );
                this.productosContainer.insertAdjacentHTML(
                    "beforeend",
                    newRowHtml
                );
            });
            this.recalcularTotales();
        },

        bindEvents() {
            if (!this.productosContainer) return;

            if (this.addProductBtn) {
                this.addProductBtn.addEventListener("click", () =>
                    this.agregarFilaProducto()
                );
            }

            // Delegación de eventos: input (cantidad, precio, descuento)
            this.productosContainer.addEventListener("input", (e) => {
                if (
                    e.target.classList.contains("input-cantidad") ||
                    e.target.classList.contains("input-precio") ||
                    e.target.classList.contains("input-descuento")
                ) {
                    this.recalcularTotales();
                }
            });

            // Delegación de eventos: cambio de producto
            this.productosContainer.addEventListener("change", (e) => {
                if (e.target.classList.contains("producto-select")) {
                    const row = e.target.closest(".detalle-row");
                    const newProductoId = parseInt(e.target.value) || null;

                    let isDuplicated = false;
                    if (newProductoId) {
                        let count = 0;
                        document
                            .querySelectorAll(".detalle-row")
                            .forEach((r) => {
                                const select =
                                    r.querySelector(".producto-select");
                                if (
                                    select.value &&
                                    parseInt(select.value) === newProductoId
                                )
                                    count++;
                            });
                        if (count > 1) isDuplicated = true;
                    }

                    if (isDuplicated) {
                        this.showTemporaryMessage(
                            "🚨 El producto ya fue agregado.",
                            "error"
                        );
                        e.target.value = "";
                    }

                    const productoSeleccionado = this.productos.find(
                        (p) => p.id === newProductoId
                    );
                    if (row) {
                        const precioInput = row.querySelector(".input-precio");
                        const descuentoInput =
                            row.querySelector(".input-descuento");
                        const cantidadInput =
                            row.querySelector(".input-cantidad");

                        if (productoSeleccionado) {
                            if (precioInput)
                                precioInput.value =
                                    this.getPrecioProducto(
                                        productoSeleccionado
                                    ).toFixed(2);
                            if (cantidadInput) cantidadInput.value = 1;
                            if (descuentoInput) descuentoInput.value = 0;
                        } else {
                            if (precioInput) precioInput.value = "0.00";
                            if (cantidadInput) cantidadInput.value = 1;
                            if (descuentoInput) descuentoInput.value = 0;
                        }
                    }

                    this.recalcularTotales();
                }
            });

            // Delegación de eventos: eliminar fila
            this.productosContainer.addEventListener("click", (e) => {
                if (e.target.closest(".remove-btn")) {
                    const row = e.target.closest(".detalle-row");
                    if (!row) return;
                    if (document.querySelectorAll(".detalle-row").length > 1) {
                        row.remove();
                    } else {
                        const select = row.querySelector(".producto-select");
                        const cantidadInput =
                            row.querySelector(".input-cantidad");
                        const precioInput = row.querySelector(".input-precio");
                        const descuentoInput =
                            row.querySelector(".input-descuento");
                        if (select) select.value = "";
                        if (cantidadInput) cantidadInput.value = 1;
                        if (precioInput) precioInput.value = "0.00";
                        if (descuentoInput) descuentoInput.value = 0;
                    }
                    this.recalcularTotales();
                }
            });
        },

        initStatusButtons() {
            const statusButtons = document.querySelectorAll(".btn-status");
            const csrfTokenMeta = document.querySelector(
                'meta[name="csrf-token"]'
            );
            const csrfToken = csrfTokenMeta
                ? csrfTokenMeta.getAttribute("content")
                : null;

            if (statusButtons.length === 0 || !csrfToken) return;

            statusButtons.forEach((button) => {
                button.addEventListener("click", async () => {
                    const id = button.dataset.id;
                    try {
                        const response = await fetch(
                            `/presupuestos/${id}/estado`,
                            {
                                method: "PATCH",
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                    "Content-Type": "application/json",
                                    Accept: "application/json",
                                },
                            }
                        );

                        if (response.ok) {
                            const data = await response.json();
                            const newStatus = data.new_status;
                            button.dataset.status = newStatus;
                            button.textContent =
                                newStatus.charAt(0).toUpperCase() +
                                newStatus.slice(1);

                            const statusClasses = {
                                facturado: ["bg-green-100", "text-green-800"],
                                pendiente: ["bg-red-100", "text-red-800"],
                            };

                            button.classList.remove(
                                "bg-red-100",
                                "text-red-800",
                                "bg-green-100",
                                "text-green-800",
                                "bg-yellow-100",
                                "text-yellow-800"
                            );
                            if (statusClasses[newStatus]) {
                                button.classList.add(
                                    ...statusClasses[newStatus]
                                );
                            } else {
                                button.classList.add(
                                    "bg-yellow-100",
                                    "text-yellow-800"
                                );
                            }
                        } else {
                            console.error(
                                "Error al actualizar el estado. Código:",
                                response.status
                            );
                        }
                    } catch (error) {
                        console.error("Error en la petición:", error);
                    }
                });
            });
        },
    };

    // Inicializar la aplicación
    PresupuestoApp.init();
});
