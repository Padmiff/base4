document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    let currentStep = 0;


    // Generate unique folio and set it to read-only input
    const folioInput = document.getElementById('folio');
    if (folioInput) {
        folioInput.value = generateUniqueFolio();
        folioInput.readOnly = true; // Make the field read-only
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
        // Obtener los valores del formulario
        var fecha_de_solicitud = document.getElementById('fecha_de_solicitud').value;
        var nombre_empresa = document.getElementById('nombre_empresa').value;
        var nombre_visitante = document.getElementById('nombre_visitante').value;
        var correo = document.getElementById('correo').value;
        var telefono = document.getElementById('telefono').value;
        var folio = document.getElementById('folio').value;
        var prioridad = document.getElementById('prioridad').value;
        var entrada_o_salida = document.getElementById('entrada_o_salida').value;
        var fecha_devolucion = document.getElementById('fecha_devolucion').value;
        var fines_utilizacion = document.getElementById('fines_utilizacion').value;
        var caracteristicas = getCaracteristicas(); // Función para obtener las características del equipo
        var evidenciaInput = document.getElementById('evidencia');
        var nombre_responsable = document.getElementById('nombre_responsable').value;
        var firma_de_responsable = document.getElementById('firma_de_responsable').value;
        var aceptacion_de_responsabilidad = document.getElementById('aceptación_de_responsabilidad').checked ? 'Aceptado' : 'No aceptado';
        var nombre_aut_ho = document.getElementById('nombre_aut_ho').value;
        var correo_aut_ho = document.getElementById('correo_aut_ho').value;
        var firma_aut_ho = document.getElementById('firma_aut_ho').value;
    
        // Establecer los valores en la vista previa
        document.getElementById('preview_fecha_de_solicitud').textContent = fecha_de_solicitud;
        document.getElementById('preview_nombre_empresa').textContent = nombre_empresa;
        document.getElementById('preview_nombre_visitante').textContent = nombre_visitante;
        document.getElementById('preview_correo').textContent = correo;
        document.getElementById('preview_telefono').textContent = telefono;
        document.getElementById('preview_folio').textContent = folio;
        document.getElementById('preview_prioridad').textContent = prioridad;
        document.getElementById('preview_entrada_o_salida').textContent = entrada_o_salida;
        document.getElementById('preview_fecha_devolucion').textContent = fecha_devolucion;
        document.getElementById('preview_fines_utilizacion').textContent = fines_utilizacion;
        document.getElementById('preview_caracteristicas').innerHTML = caracteristicas;
    
        // Mostrar la vista previa del archivo de evidencia si está disponible
        var evidenciaPreview = document.getElementById('preview_evidencia');
        if (evidenciaInput && evidenciaInput.files.length > 0) {
            var evidenciaURL = URL.createObjectURL(evidenciaInput.files[0]);
            evidenciaPreview.src = evidenciaURL;
            evidenciaPreview.style.display = 'block';
        } else {
            evidenciaPreview.style.display = 'none';
        }
    
        document.getElementById('preview_nombre_responsable').textContent = nombre_responsable;
        document.getElementById('preview_firma_de_responsable').textContent = firma_de_responsable;
        document.getElementById('preview_aceptacion_de_responsabilidad').textContent = aceptacion_de_responsabilidad;
        document.getElementById('preview_nombre_aut_ho').textContent = nombre_aut_ho;
        document.getElementById('preview_correo_aut_ho').textContent = correo_aut_ho;
        document.getElementById('preview_firma_aut_ho').textContent = firma_aut_ho;
    
        // Mostrar la vista previa y ocultar el formulario principal
        document.getElementById('form2').style.display = 'none';
        document.getElementById('previewSection').style.display = 'block';
    }


// Función para ocultar la vista previa y volver al formulario principal
window.hidePreview = function() {
    document.getElementById('form2').style.display = 'block';
    document.getElementById('previewSection').style.display = 'none';
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


    setInterval(updateDateTime, 1000);

    updateDateTime();

    window.rowCount = 1; // Inicializa un contador de filas

    window.addRow = function() {
        const table = document.getElementById('dynamicTable');
        table.style.display = 'table'; // Mostrar la tabla si está oculta
    
        const tbody = table.getElementsByTagName('tbody')[0];
        const newRow = tbody.insertRow();
    
        const cell1 = newRow.insertCell(0);
        const cell2 = newRow.insertCell(1);
        const cell3 = newRow.insertCell(2);
        const cell4 = newRow.insertCell(3);
        const cell5 = newRow.insertCell(4);
        const cell6 = newRow.insertCell(5);
    
        // Contenido de las celdas
        cell1.innerHTML = `
            <select class="form-select" name="tipo_equipo">
                <option value="Laptop">Laptop</option>
                <option value="PC de escritorio">PC de escritorio</option>
                <option value="Tableta">Tableta</option>
                <option value="Monitor/Pantalla/Proyector">Monitor/Pantalla/Proyector</option>
                <option value="Equipos de impresion y multifuncionales">Equipos de impresión y multifuncionales</option>
                <option value="Hardware (teclado/mouse/videocámaras/adaptadores)">Hardware (teclado/mouse/videocámaras/adaptadores)</option>
                <option value="Otro">Otro</option>
            </select>
        `;
        cell2.innerHTML = `<input type="text" class="form-control" name="marca" placeholder="Marca">`;
        cell3.innerHTML = `<input type="text" class="form-control" name="modelo" placeholder="Modelo">`;
        cell4.innerHTML = `<input type="text" class="form-control" name="numero_serie" placeholder="Número de serie">`;
        cell5.innerHTML = `<input type="text" class="form-control" name="pertenece_a_ho" placeholder="¿Este equipo pertenece a HO? *">`;
        cell6.innerHTML = `<button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">Eliminar</button>`;
    };
    
    window.deleteRow = function(button) {
        const row = button.closest('tr');
        row.remove();
    
        const table = document.getElementById('dynamicTable').querySelector('tbody');
        if (table.rows.length === 0) {
            document.getElementById('dynamicTable').style.display = 'none'; // Ocultar la tabla si no hay filas
        }
    };


    function generateUniqueFolio() {
        // Obtener la fecha actual en formato YYYYMMDD
        const date = new Date();
        const year = date.getFullYear();
        const month = ('0' + (date.getMonth() + 1)).slice(-2);
        const day = ('0' + date.getDate()).slice(-2);
        const dateString = `${year}${month}${day}`;
    
        // Obtener el contador actual de localStorage, si no existe, inicializarlo en 0
        let counter = localStorage.getItem('folioCounter');
        if (counter === null) {
            counter = 0;
        } else {
            counter = parseInt(counter, 10);
        }
    
        // Incrementar el contador y guardarlo en localStorage
        counter += 1;
        localStorage.setItem('folioCounter', counter);
    
        // Generar el folio único combinando la fecha y el contador
        const folio = `${dateString}${('000000' + counter).slice(-6)}`;
    
        return folio;
    }


    // dropzone-config.js

Dropzone.options.dataDropzone = {
    paramName: "file", // Nombre del parámetro que enviará el archivo al servidor
    maxFilesize: 5, // Tamaño máximo del archivo en MB
    maxFiles: null, // Número máximo de archivos (null significa sin límite)
    acceptedFiles: ".jpg,.png,.jpeg,.gif", // Tipos de archivos aceptados
    addRemoveLinks: true, // Mostrar enlaces para eliminar archivos subidos
    dictDefaultMessage: "Drop files here or click to upload", // Mensaje predeterminado
    dictRemoveFile: "Remove", // Texto del enlace para eliminar archivo
    dictFileTooBig: "File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.", // Mensaje para archivo demasiado grande
    init: function () {
        this.on("addedfile", function (file) {
            // Añadir lógica adicional al agregar un archivo si es necesario
        });
        this.on("removedfile", function (file) {
            // Añadir lógica para eliminar un archivo si es necesario
        });
        this.on("thumbnail", function (file, dataUrl) {
            // Mostrar miniatura del archivo
            $('.dz-preview').last().find('img').attr({src: dataUrl});
        });
    }
};


});