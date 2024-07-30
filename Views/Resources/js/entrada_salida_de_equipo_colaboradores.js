document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    let currentStep = 0;

    function showStep(step) {
        const formSteps = document.querySelectorAll('.form-step');
        formSteps.forEach((stepElement, index) => {
            stepElement.style.display = index === step ? 'block' : 'none';
        });
        currentStep = step;
    }

    window.showStep = showStep;

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

    window.validateAndShowPreview =  function () {
        if (validateForm()) {
            showPreview();
        }
    }


    window.hidePreview = function () {
        document.getElementById('preview').style.display = 'none';
        showStep(currentStep); // Restaurar el paso actual del formulario
    };
        
        // Inicializar la vista con el primer paso
        document.addEventListener('DOMContentLoaded', () => {
            showStep(0);
        });

        window.showPreview = function () {
            try {
                const fields = [
                    'N_colaborador', 'departamento', 'N_empleado', 'Ape_paterno', 
                    'Ape_materno', 'correo', 'telefono', 'folio', 'fecha_solicitud',
                    'prioridad', 'entrada_o_salida', 'fecha_devolucion', 
                    'fines_utilizacion', 'nombre_colab', 'firma_de_responsable', 
                    'nombre_aut_ho', 'correo_aut_ho', 'firma_aut_ho'
                ];
        
                fields.forEach(field => {
                    const value = document.getElementById(field).value;
                    document.getElementById(`preview_${field}`).textContent = value;
                });
        
                // Mostrar todas las imágenes cargadas en el contenedor de vista previa
                const previewEvidenciaContainer = document.getElementById('preview_evidencia_container');
                previewEvidenciaContainer.innerHTML = ''; // Limpiar el contenedor de vista previa antes de agregar nuevas imágenes
        
                const evidenceFiles = document.querySelectorAll('#my-dropzone .dz-preview img');
                evidenceFiles.forEach(file => {
                    const img = document.createElement('img');
                    img.src = file.src;
                    img.style.width = '100px'; // Ajustar el tamaño según sea necesario
                    img.style.margin = '5px';  // Espaciado entre imágenes
                    previewEvidenciaContainer.appendChild(img);
                });
        
                // Mostrar la tabla dinámica en la vista previa, excluyendo la columna "¿Este equipo pertenece a HO?"
                const dynamicTable = document.getElementById('dynamicTable');
                const previewTable = document.getElementById('preview_caracteristicas');
                let tableHtml = '<thead><tr><th>Tipo de equipo</th><th>Marca</th><th>Modelo</th><th>Número de serie</th></tr></thead><tbody>';
        
                const rows = dynamicTable.querySelectorAll('tbody tr');
                rows.forEach((row) => {
                    const tipoEquipoSelect = row.querySelector('select[name="tipo_equipo[]"]');
                    const marcaInput = row.querySelector('input[name="marca[]"]');
                    const modeloInput = row.querySelector('input[name="modelo[]"]');
                    const numeroSerieInput = row.querySelector('input[name="numero_serie[]"]');
        
                    const tipoEquipo = tipoEquipoSelect ? tipoEquipoSelect.value : '';
                    const marca = marcaInput ? marcaInput.value : '';
                    const modelo = modeloInput ? modeloInput.value : '';
                    const numeroSerie = numeroSerieInput ? numeroSerieInput.value : '';
        
                    tableHtml += `<tr>
                        <td>${tipoEquipo}</td>
                        <td>${marca}</td>
                        <td>${modelo}</td>
                        <td>${numeroSerie}</td>
                    </tr>`;
                });
        
                tableHtml += '</tbody>';
                previewTable.innerHTML = tableHtml;
        
                // Ocultar pasos del formulario y mostrar la vista previa
                const formSteps = document.querySelectorAll('.form-step');
                formSteps.forEach(step => step.style.display = 'none');
        
                document.getElementById('preview').style.display = 'block';
            } catch (error) {
                alert('Ocurrió un error al mostrar la vista previa.');
            }
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
    
    // Inicializar la vista con el primer paso
    showStep(0);

    Dropzone.autoDiscover = false;

    const myDropzone = new Dropzone("#my-dropzone", {
        url: "#", // No necesita URL de carga ya que se maneja solo en frontend
        addRemoveLinks: true, // Añade enlaces para eliminar archivos
        dictRemoveFile: 'Remove',
        maxFilesize: 5, // Tamaño máximo del archivo en MB
        parallelUploads: 5, // Número máximo de archivos que se pueden cargar al mismo tiempo
        autoProcessQueue: false, // No procesar automáticamente ya que no interactuamos con el servidor
        maxFiles: 5, // Número máximo de archivos permitidos
        acceptedFiles: "image/jpeg,image/png,image/jpg" // Extensiones permitidas
    });

    document.getElementById('sendEmail').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('form2'));
    
        // Agregar archivos de Dropzone a formData
        myDropzone.getAcceptedFiles().forEach(function(file, index) {
            formData.append('evidenceFiles[]', file, file.name);
            console.log(`File ${index + 1}: Name = ${file.name}, Size = ${file.size} bytes, Type = ${file.type}`);
        });
    
        // Depuración: Mostrar el contenido de formData
        for (var pair of formData.entries()) {
            if (pair[1] instanceof File) {
                console.log(`${pair[0]}: ${pair[1].name}, Size: ${pair[1].size}, Type: ${pair[1].type}`);
            } else {
                console.log(`${pair[0]}: ${pair[1]}`);
            }
        }

        // Agregar datos de la tabla dinámica a formData
    const dynamicTable = document.getElementById('dynamicTable');
    const rows = dynamicTable.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        if (index % 2 === 0) {
            const tipoEquipoSelect = row.querySelector('select[name="tipo_equipo[]"]');
            const marcaInput = row.querySelector('input[name="marca[]"]');
            const modeloInput = row.querySelector('input[name="modelo[]"]');
            const numeroSerieInput = row.querySelector('input[name="numero_serie[]"]');

            const tipoEquipo = tipoEquipoSelect ? tipoEquipoSelect.value : '';
            const marca = marcaInput ? marcaInput.value : '';
            const modelo = modeloInput ? modeloInput.value : '';
            const numeroSerie = numeroSerieInput ? numeroSerieInput.value : '';

            formData.append(`tipo_equipo[${index}]`, tipoEquipo);
            formData.append(`marca[${index}]`, marca);
            formData.append(`modelo[${index}]`, modelo);
            formData.append(`numero_serie[${index}]`, numeroSerie);
        }
    });

         // Agregar campos adicionales a formData
    formData.append('folio', document.getElementById('folio').value);
    formData.append('fecha_solicitud', document.getElementById('fecha_solicitud').value);
    formData.append('prioridad', document.getElementById('prioridad').value);
    formData.append('entrada_o_salida', document.getElementById('entrada_o_salida').value);
    formData.append('fines_utilizacion', document.getElementById('fines_utilizacion').value);
    formData.append('nombre_colab', document.getElementById('nombre_colab').value);
    formData.append('firma_de_responsable', document.getElementById('firma_de_responsable').value);
    formData.append('nombre_aut_ho', document.getElementById('nombre_aut_ho').value);
    formData.append('correo_aut_ho', document.getElementById('correo_aut_ho').value);
    formData.append('firma_aut_ho', document.getElementById('firma_aut_ho').value);
    
        // Crea una solicitud AJAX para enviar los datos al script PHP
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'Views/Resources/php/enviaentrada_salida_colaborador.php', true);
    
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
                    alert('Formulario enviado correctamente.');
                }
            } else {
                alert('Error inesperado al intentar enviar el formulario.');
            }
        };
    
        // Envía los datos del formulario
        xhr.send(formData);
    });

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


    // Generate unique folio and set it to read-only input
    const folioInput = document.getElementById('folio');
    if (folioInput) {
        folioInput.value = generateUniqueFolio();
        folioInput.readOnly = true; // Make the field read-only
    }

    function generateUniqueFolio () {
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
    
        const fechaPedidoInput = document.getElementById('fecha_solicitud');
        fechaPedidoInput.value = fechaPedido;
    }
    
    setFechaPedido();
    setInterval(updateDateTime, 1000);
    updateDateTime();
});