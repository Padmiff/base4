document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    let currentStep = 0;
    

            document.addEventListener('DOMContentLoaded', function() {
                loadDepartamentos();
            });
            
            
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
                    // Limpiar la tabla dinámica previa en la vista previa
                    const previewTableBody = document.getElementById('preview_dynamicTable').getElementsByTagName('tbody')[0];
                    previewTableBody.innerHTML = '';
            
                    // Obtener la referencia a la tabla dinámica original
                    const dynamicTableBody = document.getElementById('dynamicTable').getElementsByTagName('tbody')[0];
            
                    // Iterar sobre cada fila de la tabla dinámica y agregarla a la vista previa
                    for (let i = 0; i < dynamicTableBody.rows.length; i++) {
                        const newRow = previewTableBody.insertRow();
                        for (let j = 0; j < dynamicTableBody.rows[i].cells.length; j++) {
                            const cell = newRow.insertCell();
                            // Copiar el contenido HTML de la celda
                            cell.innerHTML = dynamicTableBody.rows[i].cells[j].innerHTML;
                        }
                    }
            
                    // Iterar sobre los otros elementos del formulario y actualizar la vista previa
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
                const fechaPedidoInput = document.getElementById('fecha_reporte');
                fechaPedidoInput.value = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
            }

            setFechaPedido();
            setInterval(updateDateTime, 1000);
            updateDateTime();

window.rowCount = 1; // Inicializa un contador de filas al cargar la página

// Función para agregar una nueva fila a la tabla dinámica
// Función para agregar una nueva fila a la tabla dinámica
window.addRow = function() {
    const table = document.getElementById('dynamicTable');
    table.style.display = 'table'; // Mostrar la tabla si estaba oculta

    const tbody = table.getElementsByTagName('tbody')[0];
    const lastRow = tbody.rows[tbody.rows.length - 1]; // Obtén la última fila actual
    const lastRowCount = lastRow ? parseInt(lastRow.cells[0].querySelector('input[type="text"][name="numero"]').value) : 0;

    const newRow = tbody.insertRow();

    const cell1 = newRow.insertCell(0);
    const cell2 = newRow.insertCell(1);
    const cell3 = newRow.insertCell(2);
    const cell4 = newRow.insertCell(3);
    const cell5 = newRow.insertCell(4);

    cell1.innerHTML = `<input type="text" class="form-control" name="numero" value="${lastRowCount + 1}" readonly>`; // Usa el contador como valor
    cell2.innerHTML = `<input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" min="0">`; // Agregado min="0" para evitar números negativos
    cell3.innerHTML = `<input type="text" class="form-control" name="unidad[]" placeholder="Unidad">`;
    cell4.innerHTML = `<textarea class="form-control" name="descripcion[]" placeholder="Descripción"></textarea>`;
    cell5.innerHTML = `<button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">Eliminar</button>`;
};


// Función para eliminar una fila de la tabla dinámica
window.deleteRow = function(button) {
    const row = button.closest('tr'); // Encuentra la fila más cercana al botón
    const tbody = row.parentNode;
    row.remove(); // Elimina la fila del DOM

    // Actualiza los números de partida después de eliminar la fila
    updateRowNumbers(tbody);

    // Oculta la tabla si ya no hay filas
    const table = document.getElementById('dynamicTable');
    if (tbody.rows.length === 0) {
        table.style.display = 'none';
    }
};

// Función para actualizar los números de partida después de eliminar una fila
function updateRowNumbers(tbody) {
    // Recorre todas las filas del tbody y actualiza los números de partida
    Array.from(tbody.rows).forEach((row, index) => {
        const cell1 = row.cells[0];
        if (cell1.querySelector('input[type="text"][name="numero"]')) {
            cell1.querySelector('input[type="text"][name="numero"]').value = index + 1;
        }
    });
}

        });


        // Función para generar un folio único
        function generateUniqueFolio(formIdentifier) {
            // Obtener la fecha actual en formato YYYYMMDD
            const date = new Date();
            const year = date.getFullYear();
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const day = ('0' + date.getDate()).slice(-2);
            const dateString = `${year}${month}${day}`;
        
            // Obtener el contador actual de localStorage, si no existe, inicializarlo en 0
            let counter = localStorage.getItem(`${formIdentifier}_folioCounter`);
            if (counter === null) {
                counter = 0;
            } else {
                counter = parseInt(counter, 10);
            }
        
            // Incrementar el contador y guardarlo en localStorage
            counter += 1;
            localStorage.setItem(`${formIdentifier}_folioCounter`, counter);
        
            // Generar el folio único combinando la fecha, identificador y el contador
            const folio = `${formIdentifier}-${dateString}${('000000' + counter).slice(-6)}`;
        
            return folio;
        }
        
        document.addEventListener('DOMContentLoaded', function () {
            const steps = document.querySelectorAll('.form-step');
            let currentStep = 0;
        
            // Identificador único para el formulario (puedes ajustarlo según tu estructura)
            const formIdentifier = 'INC'; // Ejemplo: 'INC' para incidencias
        
            // Generate unique folio and set it to read-only input
            const folioInput = document.getElementById('folio');
            if (folioInput) {
                folioInput.value = generateUniqueFolio(formIdentifier);
                folioInput.readOnly = true; // Hacer el campo de solo lectura
            }
    // Resto del código para el manejo de pasos, vista previa, etc.
});
