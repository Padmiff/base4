document.addEventListener('DOMContentLoaded', function () {
    const steps = document.querySelectorAll('.form-step');
    let currentStep = 0;
    

            document.addEventListener('DOMContentLoaded', function() {
                loadDepartamentos();
            });
            
            

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

            window.validateAndShowPreview =  function () {
                if (validateForm()) {
                    showPreview();
                }
            }


            window.showPreview = function () {
                try {
                    // Obtener los valores del formulario estáticos
                    const N_colaborador = document.getElementById('N_colaborador').value;
                    const departamento = document.getElementById('departamento').value;
                    const N_empleado = document.getElementById('N_empleado').value;
                    const Ape_paterno = document.getElementById('Ape_paterno').value;
                    const Ape_materno = document.getElementById('Ape_materno').value;
                    const correo = document.getElementById('correo').value;
                    const telefono = document.getElementById('telefono').value;
                    const folio = document.getElementById('folio').value;
                    const Prioridad = document.getElementById('Prioridad').value;
                    const gestiona = document.getElementById('gestiona').value;
                    const solicitando = document.getElementById('solicitando').value;
                    const fecha_pedido = document.getElementById('fecha_pedido').value;
                    const fecha_entrega = document.getElementById('fecha_entrega').value;
                    const fines_utilizacion = document.getElementById('fines_utilizacion').value;
                    const nombre_solicitud = document.getElementById('nombre_solicitud').value;
                    const firma_solicitud = document.getElementById('firma_solicitud').value;
                    const nombre_jefe = document.getElementById('nombre_jefe').value;
                    const correo_jefe = document.getElementById('correo_jefe').value;
                    const firma_jefe_recibe = document.getElementById('firma_jefe_recibe').value;
                    const nombre_recibe = document.getElementById('nombre_recibe').value;
                    const firma_recibe = document.getElementById('firma_recibe').value;
            
                    // Obtener la tabla dinámica y su vista previa
                    const dynamicTable = document.getElementById('dynamicTable');
                    const preview_dynamicTable = document.getElementById('preview_dynamicTable');
            
                    if (!dynamicTable) {
                        throw new Error('No se encontró la tabla dinámica.');
                    }
            
                    if (!preview_dynamicTable) {
                        throw new Error('No se encontró el contenedor para la vista previa de la tabla dinámica.');
                    }
            
                    // Clonar el contenido de la tabla dinámica
                    const clonedTable = dynamicTable.cloneNode(true);
            
                    // Obtener el cuerpo de la tabla clonada
                    const clonedBody = clonedTable.querySelector('tbody');
            
                    // Recorrer las filas de la tabla clonada y ajustar el contenido visible
                    Array.from(clonedBody.rows).forEach(row => {
                        // Recorrer las celdas de cada fila
                        Array.from(row.cells).forEach(cell => {
                            // Reemplazar los elementos interactivos con su contenido estático
                            const interactiveElements = cell.querySelectorAll('input, textarea, select');
                            if (interactiveElements.length > 0) {
                                // Limpiar las celdas de los elementos interactivos
                                interactiveElements.forEach(element => {
                                    const textContent = element.tagName === 'INPUT' ? element.value : element.textContent;
                                    cell.textContent = textContent;
                                });
                            }
                            // Eliminar botones de eliminar en la tabla clonada
                            const deleteButtons = cell.querySelectorAll('.btn-danger');
                            deleteButtons.forEach(button => button.parentNode.removeChild(button));
                        });
                    });
            
                    // Limpiar la tabla previa antes de agregar el nuevo contenido
                    preview_dynamicTable.innerHTML = '';
                    preview_dynamicTable.appendChild(clonedTable);
            
                    // Establecer los valores estáticos en la vista previa
                    document.getElementById('preview_N_colaborador').textContent = N_colaborador;
                    document.getElementById('preview_departamento').textContent = departamento;
                    document.getElementById('preview_N_empleado').textContent = N_empleado;
                    document.getElementById('preview_Ape_paterno').textContent = Ape_paterno;
                    document.getElementById('preview_Ape_materno').textContent = Ape_materno;
                    document.getElementById('preview_correo').textContent = correo;
                    document.getElementById('preview_telefono').textContent = telefono;
                    document.getElementById('preview_folio').textContent = folio;
                    document.getElementById('preview_Prioridad').textContent = Prioridad;
                    document.getElementById('preview_gestiona').textContent = gestiona;
                    document.getElementById('preview_solicitando').textContent = solicitando;
                    document.getElementById('preview_fecha_pedido').textContent = fecha_pedido;
                    document.getElementById('preview_fecha_entrega').textContent = fecha_entrega;
                    document.getElementById('preview_fines_utilizacion').textContent = fines_utilizacion;
                    document.getElementById('preview_nombre_solicitud').textContent = nombre_solicitud;
                    document.getElementById('preview_firma_solicitud').textContent = firma_solicitud;
                    document.getElementById('preview_nombre_jefe').textContent = nombre_jefe;
                    document.getElementById('preview_correo_jefe').textContent = correo_jefe;
                    document.getElementById('preview_firma_jefe_recibe').textContent = firma_jefe_recibe;
                    document.getElementById('preview_nombre_recibe').textContent = nombre_recibe;
                    document.getElementById('preview_firma_recibe').textContent = firma_recibe;
            
                    // Ocultar todas las secciones del formulario
                    const formSteps = document.querySelectorAll('.form-step');
                    formSteps.forEach(step => step.style.display = 'none');
            
                    // Mostrar la vista previa
                    document.getElementById('preview').style.display = 'block';
                } catch (error) {
                    console.error('Error en la función showPreview:', error.message);
                    alert('Ocurrió un error al mostrar la vista previa. Por favor, revise la consola para más detalles.');
                }
            };

            window.editForm = function () {
                // Mostrar la primera sección del formulario y ocultar la vista previa
                document.getElementById('preview').style.display = 'none';
                showStep(0);
            }
            
            // Inicializar la vista con el primer paso
            document.addEventListener('DOMContentLoaded', () => {
                showStep(0);
            });

            window.submitForm = function () {
                // Recoge los valores del formulario
                var formData = new FormData(document.getElementById('form2'));
            
                // Recoge los valores de la tabla dinámica
                const dynamicTable = document.getElementById('dynamicTable');
                if (dynamicTable) {
                    const dynamicRows = dynamicTable.querySelectorAll('tbody tr');
            
                    dynamicRows.forEach((row, index) => {
                        const partida = row.querySelector('input[name="partida[]"]')?.value || '';
                        const cantidad = row.querySelector('input[name="cantidad[]"]')?.value || '';
                        const unidad = row.querySelector('input[name="unidad[]"]')?.value || '';
                        const descripcion = row.querySelector('input[name="descripcion[]"]')?.value || '';
            
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
                            alert('Formulario enviado correctamente.');
                        }
                    } else {
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
            cell4.innerHTML = `<input class="form-control" name="descripcion[]" placeholder="Descripción"></textarea>`;
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
                function generateUniqueFolio() {
                    // Obtener el último folio generado desde algún origen (base de datos, servidor, etc.)
                    // En este ejemplo, supondremos que el último folio generado fue '105'
                    let lastFolio = '50'; // Supongamos el último folio generado
                
                    // Extraer el contador del último folio generado y convertirlo a número entero
                    let lastCounter = parseInt(lastFolio, 10);
                
                    // Incrementar el contador para generar el siguiente folio consecutivo
                    let counter = lastCounter + 1;
                
                    return counter;
                }
                
                document.addEventListener('DOMContentLoaded', function () {
                    const steps = document.querySelectorAll('.form-step');
                    let currentStep = 0;
                
                    // Generate unique folio and set it to read-only input
                    const folioInput = document.getElementById('folio');
                    if (folioInput) {
                        folioInput.value = generateUniqueFolio();
                        folioInput.readOnly = true; // Make the field read-only
                    }


                   
    // Resto del código para el manejo de pasos, vista previa, etc.
});
