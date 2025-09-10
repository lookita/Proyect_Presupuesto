document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const nombreInput = document.getElementById('nombre');
    const codigoInput = document.getElementById('codigo');
    const precioInput = document.getElementById('precio');

    // Función para mostrar un mensaje de error
    const mostrarError = (input, mensaje) => {
        const errorElemento = document.createElement('p');
        errorElemento.classList.add('text-red-500', 'text-sm', 'mt-1', 'validation-error');
        errorElemento.textContent = mensaje;
        
        const contenedor = input.parentElement;
        contenedor.querySelector('.validation-error')?.remove(); // Elimina el error anterior
        contenedor.appendChild(errorElemento);
        input.classList.add('border-red-500');
    };

    // Función para limpiar los mensajes de error
    const limpiarError = (input) => {
        const contenedor = input.parentElement;
        contenedor.querySelector('.validation-error')?.remove();
        input.classList.remove('border-red-500');
    };

    // Validación en tiempo real al escribir
    nombreInput.addEventListener('input', () => {
        if (nombreInput.value.trim().length < 3) {
            mostrarError(nombreInput, 'El nombre debe tener al menos 3 caracteres.');
        } else {
            limpiarError(nombreInput);
        }
    });

    codigoInput.addEventListener('input', () => {
        if (codigoInput.value.trim().length === 0) {
            mostrarError(codigoInput, 'El código no puede estar vacío.');
        } else {
            limpiarError(codigoInput);
        }
    });

    precioInput.addEventListener('input', () => {
        const precio = parseFloat(precioInput.value);
        if (isNaN(precio) || precio <= 0) {
            mostrarError(precioInput, 'El precio debe ser un número positivo.');
        } else {
            limpiarError(precioInput);
        }
    });

    // Validación final al enviar el formulario
    form.addEventListener('submit', (e) => {
        let esValido = true;

        // Validar nombre
        if (nombreInput.value.trim().length < 3) {
            mostrarError(nombreInput, 'El nombre debe tener al menos 3 caracteres.');
            esValido = false;
        }

        // Validar código
        if (codigoInput.value.trim().length === 0) {
            mostrarError(codigoInput, 'El código no puede estar vacío.');
            esValido = false;
        }

        // Validar precio
        const precio = parseFloat(precioInput.value);
        if (isNaN(precio) || precio <= 0) {
            mostrarError(precioInput, 'El precio debe ser un número positivo.');
            esValido = false;
        }

        if (!esValido) {
            e.preventDefault(); // Detiene el envío del formulario si hay errores
        }
    });
});