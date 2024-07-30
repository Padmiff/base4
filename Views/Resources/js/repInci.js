 document.addEventListener('DOMContentLoaded', function () {
            const steps = document.querySelectorAll('.form-step');
            let currentStep = 0;

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

            // Definir la función hidePreview
            window.hidePreview = function () {
                    // Mostrar la primera sección del formulario y ocultar la vista previa
                    document.getElementById('preview').style.display = 'none';
                    showStep(0);
                }
                
                // Inicializar la vista con el primer paso
                document.addEventListener('DOMContentLoaded', () => {
                    showStep(0);
                });

            window.showPreview = function () {
                try {
                    const N_colaborador = document.getElementById('N_colaborador').value;
                    const departamento = document.getElementById('departamento').value;
                    const N_empleado = document.getElementById('N_empleado').value;
                    const Ape_paterno = document.getElementById('Ape_paterno').value;
                    const Ape_materno = document.getElementById('Ape_materno').value;
                    const correo = document.getElementById('correo').value;
                    const telefono = document.getElementById('telefono').value;
                    const folio = document.getElementById('folio').value;
                    const fecha_reporte = document.getElementById('fecha_reporte').value;
                    const tipo_inc = document.getElementById('tipo_inc').value;
                    const dep_rep = document.getElementById('dep_rep').value;
                    const descrip_inc = document.getElementById('descrip_inc').value;
                    const fecha_atencion = document.getElementById('fecha_atencion').value;
                    const firma_de_conformidad = document.getElementById('firma_de_conformidad').value;
            
                    document.getElementById('preview_N_colaborador').textContent = N_colaborador;
                    document.getElementById('preview_departamento').textContent = departamento;
                    document.getElementById('preview_N_empleado').textContent = N_empleado;
                    document.getElementById('preview_Ape_paterno').textContent = Ape_paterno;
                    document.getElementById('preview_Ape_materno').textContent = Ape_materno;
                    document.getElementById('preview_correo').textContent = correo;
                    document.getElementById('preview_telefono').textContent = telefono;
                    document.getElementById('preview_folio').textContent = folio;
                    document.getElementById('preview_fecha_reporte').textContent = fecha_reporte;
                    document.getElementById('preview_tipo_inc').textContent = tipo_inc;
                    document.getElementById('preview_dep_rep').textContent = dep_rep;
                    document.getElementById('preview_descrip_inc').textContent = descrip_inc;
                    document.getElementById('preview_fecha_atencion').textContent = fecha_atencion;
                    document.getElementById('preview_firma_de_conformidad').textContent = firma_de_conformidad;
            
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
            
                    // Ocultar pasos del formulario y mostrar la vista previa
                    const formSteps = document.querySelectorAll('.form-step');
                    formSteps.forEach(step => step.style.display = 'none');
            
                    document.getElementById('preview').style.display = 'block';
                } catch (error) {
                    console.error('Error in showPreview function:', error);
                    alert('Ocurrió un error al mostrar la vista previa. Por favor, revise la consola para más detalles.');
                }
            };
            

            window.editForm = function () {
                // Mostrar la primera sección del formulario y ocultar la vista previa
                document.getElementById('preview').style.display = 'none';
                showStep(0);
            }
            
            // Inicializar la vista con el primer paso
            showStep(0);

            Dropzone.autoDiscover = false;

            const myDropzone = new Dropzone("#my-dropzone", {
                url: "#", // No necesita URL de carga ya que se maneja solo en frontend
                addRemoveLinks: true, // Añade enlaces para eliminar archivos
                dictRemoveFile: 'Remove',
                maxFilesize: 5, // Tamaño máximo del archivo en MB
                parallelUploads: 3, // Número máximo de archivos que se pueden cargar al mismo tiempo
                autoProcessQueue: false, // No procesar automáticamente ya que no interactuamos con el servidor
                maxFiles: 3, // Número máximo de archivos permitidos
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
            
                // Crea una solicitud AJAX para enviar los datos al script PHP
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'Views/Resources/php/enviarreporte_de_incidencias.php', true);
            
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
            
                const fechaPedidoInput = document.getElementById('fecha_reporte');
                fechaPedidoInput.value = fechaPedido;
            }
            
            setFechaPedido();
            setInterval(updateDateTime, 1000);
            updateDateTime();
        });