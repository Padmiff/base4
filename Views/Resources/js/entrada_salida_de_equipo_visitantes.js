document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    let currentStep = 0;


    // Generate unique folio and set it to read-only input
    const folioInput = document.getElementById('folio');
    if (folioInput) {
        folioInput.value = generateUniqueFolio();
        folioInput.readOnly = true; // Make the field read-only
    }
    
    window.showStep = function (step) {
        const formSteps = document.querySelectorAll('.form-step');
        formSteps.forEach((stepElement, index) => {
            stepElement.style.display = index === step ? 'block' : 'none';
        });
        currentStep = step;
    }

    window.nextStep = function () {
        const formSteps = document.querySelectorAll('.form-step');
        if (currentStep < formSteps.length - 1) {
            showStep(currentStep + 1);
        }
    }

    window.prevStep = function() {
        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        window.showPreview = function () {
            // Tu código aquí
        };
    });

    window.showPreview = function () {
        document.getElementById('preview_fecha_de_solicitud').textContent = document.getElementById('fecha_de_solicitud').value;
        document.getElementById('preview_nombre_empresa').textContent = document.getElementById('nombre_empresa').value;
        document.getElementById('preview_nombre_visitante').textContent = document.getElementById('nombre_visitante').value;
        document.getElementById('preview_correo').textContent = document.getElementById('correo').value;
        document.getElementById('preview_telefono').textContent = document.getElementById('telefono').value;
        document.getElementById('preview_folio').textContent = document.getElementById('folio').value;
        document.getElementById('preview_prioridad').textContent = document.getElementById('prioridad').value;
        document.getElementById('preview_entrada_o_salida').textContent = document.getElementById('entrada_o_salida').value;
        document.getElementById('preview_fecha_devolucion').textContent = document.getElementById('fecha_devolucion').value;
        document.getElementById('preview_fines_utilizacion').textContent = document.getElementById('fines_utilizacion').value;
    
        // Actualiza la tabla dinámica
        const dynamicTable = document.getElementById('dynamicTable');
        const previewDynamicTable = document.getElementById('preview_dynamicTable');
        previewDynamicTable.querySelector('tbody').innerHTML = '';
        
        for (let i = 0; i < dynamicTable.rows.length; i++) {
            const row = dynamicTable.rows[i];
            const cells = row.cells;
            if (cells.length > 0) {
                const newRow = previewDynamicTable.insertRow();
                for (let j = 0; j < cells.length; j++) {
                    const newCell = newRow.insertCell();
                    newCell.textContent = cells[j].textContent;
                }
            }
        }
    
        // Muestra la imagen de evidencia si se ha cargado
        const evidenceFile = document.querySelector('#my-dropzone .dz-preview img');
        if (evidenceFile) {
            document.getElementById('preview_evidencia').src = evidenceFile.src;
            document.getElementById('preview_evidencia').style.display = 'block';
        }
    
        // Actualiza otros campos de vista previa
        document.getElementById('preview_nombre_responsable').textContent = document.getElementById('nombre_responsable').value;
        document.getElementById('preview_firma_de_responsable').textContent = document.getElementById('firma_de_responsable').value;
        document.getElementById('preview_aceptacion_de_responsabilidad').textContent = document.getElementById('aceptacionRes').checked ? 'Aceptado' : 'No Aceptado';
        document.getElementById('preview_nombre_aut_ho').textContent = document.getElementById('nombre_aut_ho').value;
        document.getElementById('preview_correo_aut_ho').textContent = document.getElementById('correo_aut_ho').value;
        document.getElementById('preview_firma_aut_ho').textContent = document.getElementById('firma_aut_ho').value;
    
        // Mostrar la vista previa y ocultar el formulario
        document.querySelector('.form-step-active').style.display = 'none';
        document.getElementById('previewSection').style.display = 'block';
    }
    
    window.hidePreview = function () {
        document.getElementById('previewSection').style.display = 'none';
        document.querySelector('.form-step').style.display = 'block';
    }

// Función para obtener las características del equipo y formatearlas como HTML
window. getCaracteristicas = function() {
    var table = document.getElementById('dynamicTable');
    var rows = table.getElementsByTagName('tr');
    var caracteristicasHTML = '';

    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            var tipo_equipo = cells[0].textContent;
            var marca = cells[1].textContent;
            var modelo = cells[2].textContent;
            var num_serie = cells[3].textContent;

            caracteristicasHTML += '<tr>';
            caracteristicasHTML += '<td>' + tipo_equipo + '</td>';
            caracteristicasHTML += '<td>' + marca + '</td>';
            caracteristicasHTML += '<td>' + modelo + '</td>';
            caracteristicasHTML += '<td>' + num_serie + '</td>';
            caracteristicasHTML += '<td>' + pertenece_a_ho + '</td>';
            
            caracteristicasHTML += '</tr>';
        }
    }

    return caracteristicasHTML;
}

    window.editForm = function() {
        // Ocultar la vista previa y mostrar la sección anterior del formulario
        document.getElementById('preview').style.display = 'none';
        if (currentStep > 0) {
            showStep(currentStep - 1);
        } else {
            console.error('No se puede retroceder más, ya estás en el primer paso del formulario.');
        }
    };

    window.submitForm = function () {
        // Recoge los valores del formulario
        var formData = new FormData(document.getElementById('form2'));

        // Recoge los valores de la tabla dinámica
        const dynamicTable = document.getElementById('dynamicTable');
        if (dynamicTable) {
            const dynamicRows = dynamicTable.querySelectorAll('tbody tr');

            dynamicRows.forEach((row, index) => {
                const tipo_equipo = row.querySelector('input[name="tipo_equipo[]"]')?.value || '';
                const marca = row.querySelector('input[name="marca[]"]')?.value || '';
                const modelo = row.querySelector('input[name="modelo[]"]')?.value || '';
                const numero_serie = row.querySelector('input[name="numero_serie[]"]')?.value || '';
                const pertenece_a_ho = row.querySelector('input[name="pertenece_a_ho[]"]')?.value || '';

                formData.append(`tipo_equipo[${index}]`, tipo_equipo);
                formData.append(`marca[${index}]`, marca);
                formData.append(`modelo[${index}]`, modelo);
                formData.append(`numero_serie[${index}]`, numero_serie);
                formData.append(`pertenece_a_ho[${index}]`, pertenece_a_ho);
            });
        }

        // Crea una solicitud AJAX para enviar los datos al script PHP
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'Views/Resources/php/enviarentrada_salida_visitante.php', true);

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
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
    
        const fechaPedido = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    
        const fechaPedidoInput = document.getElementById('fecha_de_solicitud');
        fechaPedidoInput.value = fechaPedido;
    }
    
    setFechaPedido();
    setInterval(updateDateTime, 1000);
    updateDateTime();

    let rowCount = 1;

    window.addRow = function () {
        const table = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0];
        
        // First row with inputs
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td>
                <select name="tipo_equipo[]" class="form-select form-select-sm">
                    <option>-- Seleccione --</option>
                    <option value="Laptop">Laptop</option>
                    <option value="PC de escritorio">PC de escritorio</option>
                    <option value="Tableta">Tableta</option>
                    <option value="Monitor/Pantalla/Proyector">Monitor/Pantalla/Proyector</option>
                    <option value="Equipos de impresión y multifuncionales">Equipos de impresión y multifuncionales</option>
                    <option value="Hardware (teclado/mouse/videocámaras/adaptadores)">Hardware (teclado/mouse/videocámaras/adaptadores)</option>
                    <option value="Otro">Otro</option>
                </select>
            </td>
            <td><input class="form-control form-control-sm" type="text" name="marca[]"></td>
            <td><input class="form-control form-control-sm" type="text" name="modelo[]"></td>
            <td><input class="form-control form-control-sm" type="text" name="numero_serie[]"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button></td>
        `;
    
        // Second row with radio buttons
        const newRadioRow = table.insertRow();
        newRadioRow.innerHTML = `
            <td colspan="5">
                <label>¿Este equipo pertenece a HO?<span class="text-danger"> *</span></label>
                <div>
                    <input type="radio" class="form-check-input me-2" name="pertenece_a_ho_${rowCount}" value="si" required>Si
                    <input type="radio" class="form-check-input me-2" name="pertenece_a_ho_${rowCount}" value="no" required>No
                </div>
            </td>
        `;
    
        rowCount++;
    }
    
    window.removeRow = function (button) {
        const row = button.parentNode.parentNode;
        const radioRow = row.nextElementSibling;
        row.parentNode.removeChild(row);
        if (radioRow) {
            row.parentNode.removeChild(radioRow);
        }
    }


    function generateUniqueFolio() {
        // Obtener el contador actual de localStorage, si no existe, inicializarlo en 50
        let counter = localStorage.getItem('folioCounter');
        if (counter === null) {
            counter = 50; // Comenzar desde 50
        } else {
            counter = parseInt(counter, 10);
        }
    
        // Generar el folio único a partir del contador
        const folio = counter;
    
        // Incrementar el contador y guardarlo en localStorage
        localStorage.setItem('folioCounter', counter + 1);
    
        return folio;
    }

});