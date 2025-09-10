document.addEventListener('DOMContentLoaded', () => {
    // Seleccionar el formulario por ID
    const form = document.getElementById('parking-form');
    if (!form) {
        console.error('El formulario con ID "parking-form" no se encontró.');
        return;
    }

    // Seleccionar el botón
    const submitButton = form.querySelector('.btn-blue');
    if (!submitButton) {
        console.error('No se encontró un botón con la clase "btn-blue" dentro del formulario.');
        return;
    }

    // Crear y agregar outputDiv después del formulario
    const outputDiv = document.createElement('div');
    outputDiv.id = 'test-output';
    outputDiv.style.marginTop = '20px';
    outputDiv.style.padding = '15px';
    outputDiv.style.borderRadius = '8px';
    outputDiv.style.backgroundColor = '#e9ecef';
    outputDiv.style.display = 'none';
    form.parentNode.insertBefore(outputDiv, form.nextSibling);

    // Validar y manejar el envío del formulario
    form.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevenir el envío real para pruebas

        // Obtener valores del formulario
        const carId = document.getElementById('car_id').value.trim();
        const zoneId = document.getElementById('zone_id').value;
        const estimatedMinutes = document.getElementById('estimated_minutes').value;

        // Validaciones
        if (!carId || carId.length < 6) {
            showMessage('Por favor, ingrese una patente válida (mínimo 6 caracteres).', 'error');
            return;
        }
        if (!zoneId) {
            showMessage('Por favor, seleccione una zona.', 'error');
            return;
        }
        if (!estimatedMinutes) {
            showMessage('Por favor, ingrese un tiempo estimado.', 'error');
            return;
        }

        // Simular el inicio del estacionamiento
        const parkingData = {
            id: Math.floor(Math.random() * 1000000), // ID aleatorio
            car_id: carId,
            zone_id: zoneId,
            estimated_minutes: estimatedMinutes,
            start_time: new Date().toLocaleString('es-ES', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }),
            status: 'Activo'
        };

        // Mostrar mensaje de confirmación
        showMessage(`
            <strong>Estacionamiento Iniciado (Prueba):</strong><br>
            ID: ${parkingData.id}<br>
            Patente: ${parkingData.car_id}<br>
            Zona ID: ${parkingData.zone_id}<br>
            Tiempo Estimado: ${parkingData.estimated_minutes}<br>
            Hora de Inicio: ${parkingData.start_time}<br>
            Estado: ${parkingData.status}
        `, 'success');

        // Opcional: Log en consola para debugging
        console.log('Datos del estacionamiento:', parkingData);
    });

    // Función para mostrar mensajes
    function showMessage(message, type) {
        outputDiv.style.display = 'block';
        outputDiv.innerHTML = message;
        outputDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
        outputDiv.style.color = type === 'success' ? '#155724' : '#721c24';
        outputDiv.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
    }
});