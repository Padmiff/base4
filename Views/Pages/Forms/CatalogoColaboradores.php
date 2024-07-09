            <!-- Contenido principal -->
            <header class="mt-4 text-center">
                <h1>CATÁLOGO DE COLABORADORES</h1>
            </header>
            <div class="col-md-6 offset-md-3"> <!--<div class="col-12 order-xxl-3"> -->
                <form id="form1" onsubmit="event.preventDefault(); showPreview();">
                    <div class="form-group">
                        <label for="N_empleado">N de colaborador<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control mb-2" id="N_empleado" placeholder="N de colaborador" required>
                        <button type="button" class="btn btn-primary" id="verificar">Verificar</button>
                    </div>
                    <div class="form-group">
                        <label for="departamento">Departamento<span class="text-danger"> *</span></label>
                        <select class="form-select" id="departamento" required>
                            <option value="">Selecciona un departamento</option>
                            <!-- Opciones de departamento se cargarán dinámicamente desde JavaScript -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Nom_colab">Nombre(s) de colaborador<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="Nom_colab" placeholder="Nombre de colaborador" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_paterno">Apellido paterno<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="apellido_paterno" placeholder="Apellido paterno" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido_materno">Apellido materno<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="apellido_materno" placeholder="Apellido materno" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo electrónico<span class="text-danger"> *</span></label>
                        <input type="email" class="form-control" id="correo" placeholder="Correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <label for="numero">Número de teléfono de contacto<span class="text-danger"> *</span></label>
                        <input type="tel" class="form-control" id="numero" placeholder="Número de teléfono" required>
                    </div>
                    <div class="form-group">
                        <label for="aviso">Aviso de privacidad<span class="text-danger"> *</span></label>
                        <p id="aviso">La empresa COMPAÑIA HARINERA DE ORIENTE S.A. DE C.V. con domicilio
                            ubicado en PROLONGACIÓN HÉROES DE NACOZARI # 8002 COLONIA ZONA INDUSTRIAL ANEXA A LA LOMA,
                            C.P. 72230 PUEBLA, PUEBLA utilizará sus datos personales recabados con los siguientes fines:
                            fines económicos, fines personales, fines laborales, fines sociales, fines educativos, fines bancarios,
                            fines de marketing. Para mayor información sobre el tratamiento de sus datos personales usted puede acudir
                            al siguiente domicilio: PROLONGACIÓN HÉROES DE NACOZARI # 8002 COLONIA ZONA INDUSTRIAL ANEXA A LA LOMA, C.P.
                            72230 PUEBLA, PUEBLA.</p>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="aceptarAviso" required>
                            <label class="form-check-label" for="aceptarAviso">Acepto el aviso de privacidad<span class="text-danger"> *</span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-primary me-2" onclick="showPreview()">Revisar las respuestas</button>
                                <button type="button" class="btn btn-secondary" onclick="openEmailPopup()">Agregar correos adicionales</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Sección de vista previa -->
                <div id="preview" class="mt-4" style="display: none;">
                    <h2>Vista previa de respuestas</h2>
                    <p>N de empleado: <span id="preview_N_empleado"></span></p>
                    <p>Departamento: <span id="preview_departamento"></span></p>
                    <p>Nombre de colaborador: <span id="preview_Nom_colab"></span></p>
                    <p>Apellido paterno: <span id="preview_apellido_paterno"></span></p>
                    <p>Apellido materno: <span id="preview_apellido_materno"></span></p>
                    <p>Correo electrónico: <span id="preview_correo"></span></p>
                    <p>Número de teléfono de contacto: <span id="preview_numero"></span></p>
                    <button type="button" class="btn btn-secondary" onclick="editForm()">Editar respuestas</button>
                    <button type="button" class="btn btn-success" onclick="submitForm()" onclick="submitFormEmail()">Enviar formulario</button>
                </div>
            </div>
            <script src="Views/Resources/js/script.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('verificar').addEventListener('click', verificarColaborador);
                });


                function verificarColaborador() {
                    var numColaborador = document.getElementById('N_empleado').value;

                    // Realizar la petición AJAX para obtener datos del colaborador
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'Views/Resources/php/buscar_colaborador.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                document.getElementById('departamento').value = response.data.departamento;
                                document.getElementById('Nom_colab').value = response.data.nombrecolaborador;
                                document.getElementById('apellido_paterno').value = response.data.apellidopaterno;
                                document.getElementById('apellido_materno').value = response.data.apellidomaterno;
                                document.getElementById('correo').value = response.data.emailcolaborador;
                                document.getElementById('numero').value = response.data.numero_telefono;
                            } else {
                                alert('No se encontraron datos para el número de colaborador proporcionado.');
                            }
                        }
                    };
                    xhr.send('N_colaborador=' + numColaborador);
                }
            </script>