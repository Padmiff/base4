document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    let currentStep = 0;

    document.addEventListener('DOMContentLoaded', function() {
        loadDepartamentos();
    });
    
    function loadDepartamentos() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'Views/Resources/php/departamentos.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var departamentos = JSON.parse(xhr.responseText);
                var selectDepartamento = document.getElementById('departamento');
    
                departamentos.forEach(function(depto) {
                    var option = document.createElement('option');
                    option.value = depto.idDepto;
                    option.textContent = depto.nombre;
                    selectDepartamento.appendChild(option);
                });
            } else {
                console.error('Error al obtener los departamentos:', xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Error de red al intentar obtener los departamentos');
        };
        xhr.send();
    }
    function showStep(index) {
        steps.forEach((step, i) => {
            step.style.display = i === index ? 'block' : 'none';
        });
        currentStep = index; // Actualizar el paso actual
    }

    window.nextStep = function() {
        showStep(currentStep + 1);
    };

    window.prevStep = function() {
        showStep(currentStep - 1);
    };


window.showPreview = function() {
    const formGroups = document.querySelectorAll('.form-step-active .form-group');
    const previewSection = document.getElementById('preview');

    if (previewSection) {
        formGroups.forEach(formGroup => {
            const input = formGroup.querySelector('input, select, textarea');
            const previewElement = document.getElementById('preview_' + input.id);

            if (input && previewElement) {
                previewElement.textContent = input.value;
            } else {
                console.error('Elemento de formulario no encontrado en la vista previa');
            }
        });

        // Ocultar todos los pasos del formulario excepto la vista previa
        document.querySelectorAll('.form-step').forEach(step => {
            if (step !== previewSection) {
                step.style.display = 'none';
            }
        });

        // Mostrar la vista previa
        previewSection.style.display = 'block';
    } else {
        console.error('Sección de vista previa no encontrada.');
    }
};

window.editForm = function() {
    // Ocultar la vista previa y mostrar el último paso del formulario
    document.querySelectorAll('.form-step').forEach(step => {
        step.style.display = 'block';
    });
    
    document.getElementById('preview').style.display = 'none';
};
    

    window.submitForm = function () {
        // Recoge los valores del formulario
        var formData = new FormData(document.getElementById('form1'));

        // Recoge los valores de la tabla dinámica
        const dynamicTable = document.getElementById('dynamicTable');
        if (dynamicTable) {
            const dynamicRows = dynamicTable.querySelectorAll('tbody tr');

            dynamicRows.forEach((row, index) => {
                const partida = row.querySelector('input[name="partida[]"]')?.value || '';
                const cantidad = row.querySelector('input[name="cantidad[]"]')?.value || '';
                const unidad = row.querySelector('input[name="unidad[]"]')?.value || '';
                const descripcion = row.querySelector('textarea[name="descripcion[]"]')?.value || '';

                formData.append(`partida[${index}]`, partida);
                formData.append(`cantidad[${index}]`, cantidad);
                formData.append(`unidad[${index}]`, unidad);
                formData.append(`descripcion[${index}]`, descripcion);
            });
        }

        // Crea una solicitud AJAX para enviar los datos al script PHP
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'Views/Resources/php/enviarsolicitudmercan.php', true);

        // Define qué hacer cuando la respuesta es recibida
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        alert(response.message); // Mensaje de éxito
                    } else if (response.error) {
                        alert(response.error); // Mensaje de error
                    }
                } catch (e) {
                    console.error('Error al parsear la respuesta JSON:', e);
                    alert('Error inesperado al procesar la respuesta del servidor.');
                }
            } else {
                console.error('Error al enviar la solicitud AJAX:', xhr.statusText);
                alert('Error inesperado al intentar enviar el formulario.');
            }
        };

        // Envía los datos del formulario
        xhr.send(formData);
    };

    function updateDateTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();

        const datetimeElement = document.getElementById('datetime');
        if (datetimeElement) {
            datetimeElement.textContent = `${date} ${time}`;
        }
    }

    function setFechaPedido() {
        const now = new Date();
        const fechaPedidoInput = document.getElementById('fecha_pedido');
        fechaPedidoInput.value = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
    }

    setFechaPedido();
    setInterval(updateDateTime, 1000);
    updateDateTime();

    window.rowCount = 1; // Inicializa un contador de filas

    window.addRow = function() {
        const table = document.getElementById('dynamicTable');
        table.style.display = 'table'; // Mostrar la tabla

        const tbody = table.getElementsByTagName('tbody')[0];
        const newRow = tbody.insertRow();

        const cell1 = newRow.insertCell(0);
        const cell2 = newRow.insertCell(1);
        const cell3 = newRow.insertCell(2);
        const cell4 = newRow.insertCell(3);
        const cell5 = newRow.insertCell(4);

        cell1.innerHTML = `<input type="text" name="numero" value="${window.rowCount}" readonly>`; // Usa el contador como valor
        cell2.innerHTML = `<input type="number" name="cantidad[]" placeholder="Cantidad">`;
        cell3.innerHTML = `<input type="text" name="unidad[]" placeholder="Unidad">`;
        cell4.innerHTML = `<textarea name="descripcion[]" placeholder="Descripción"></textarea>`;
        cell5.innerHTML = `<button type="button" onclick="deleteRow(this)">Eliminar</button>`;

        window.rowCount++; // Incrementa el contador después de agregar la fila
    };

    window.deleteRow = function(button) {
        const row = button.closest('tr');
        row.remove();
        const table = document.getElementById('dynamicTable').querySelector('tbody');
        if (table.rows.length === 0) {
            document.getElementById('dynamicTable').style.display = 'none';
        }
    };
});
