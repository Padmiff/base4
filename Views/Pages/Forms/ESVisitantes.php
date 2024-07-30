<div class="card mb-3">

    <head>
        <link rel="stylesheet" href="Views/Resources/css/catalogo_de_colaboradores.css">
        <link rel="stylesheet" href="Views/Resources/vendors/dropzone/dropzone.css">
    </head>

    <div class="card-header z-1 col-md-8 mx-auto">
        <header class="">
            <h1 class="text-primary">F-GES-05 Entrada/salida de equipo de computo (visitantes) REV. 01</h1>
            <h5 class="text-600">Formato de captura para el control de Entrada y Salida de equipo de cómputo y/o electrónicos, para visitantes</h5>
        </header>
    </div>

    <div class="card-body col-md-8 mx-auto">
        <form id="form2" onsubmit="event.preventDefault(); showPreview();">
            <!-- Step 1 -->
            <div class="form-step form-step-active">
                <div class="col-md-8 mx-auto">
                    <label for="fecha_de_solicitud">Fecha de solicitud<span class="text-danger"> *</span></label>
                    <input class="form-control datetimepicker form-control form-control-sm" type="text" id="fecha_de_solicitud" name="fecha_de_solicitud" readonly>
                    <p class="fs-10 mb-0">Fecha y hora en la que se realiza la solicitud</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="nombre_empresa">Nombre de la empresa<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="nombre_empresa" placeholder="" required>
                    <p class="fs-10 mb-0">Nombre de la empresa a la que representa</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="nombre_visitante">Nombre de visitante<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="nombre_visitante" placeholder="" required>
                    <p class="fs-10 mb-0">Nombre completo del visitante, incluye apellidos</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="correo">Correo electrónico<span class="text-danger"> *</span></label>
                    <input type="email" class="form-control form-control-sm" id="correo" placeholder="" required>
                    <p class="fs-10 mb-0">Correo electrónico de contacto del visitante</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="telefono">Número de teléfono<span class="text-danger"> *</span></label>
                    <input type="tel" class="form-control form-control-sm" id="telefono" placeholder="(000) 000-0000" required>
                    <p class="fs-10 mb-0">Número de teléfono de contacto del visitante</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
                </div>

            </div>

            <!-- Step 2 -->
            <div class="form-step ">
                <div class="col-md-8 mx-auto">
                    <label for="folio">Folio<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="folio" placeholder="Folio" required readonly>
                    <p class="fs-10 mb-0">Número de folio</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="prioridad">Propiedad del equipo<span class="text-danger"> *</span></label>
                    <select class="form-select form-select-sm" id="prioridad" required>
                        <option>-- Seleccione --</option>
                        <option>Harinera de Oriente</option>
                        <option>Uso personal</option>
                    </select>
                    <p class="fs-10 mb-0">Seleccione quién es el propietario del equipo</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="entrada_o_salida">¿Entrada o salida?<span class="text-danger"> *</span></label>
                    <select class="form-select form-select-sm" id="entrada_o_salida" required>
                        <option>-- Seleccione --</option>
                        <option>Entrada</option>
                        <option>Salida</option>
                    </select>
                    <p class="fs-10 mb-0">Seleccione una opción</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="fecha_devolucion">Fecha de devolucion<span class="text-danger"> *</span></label>
                    <input class="form-control datetimepicker form-control form-control-sm" id="fecha_devolucion" name="fecha_devolucion" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' type="date">
                    <p class="fs-10 mb-0">Introduzca la fecha de devolución</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="fines_utilizacion">Motivo<span class="text-danger"> *</span></label>
                    <textarea class="form-control" rows="6" cols="50" id="fines_utilizacion" placeholder="" style="resize: none;"></textarea>
                    <p class="fs-10 mb-0">Escriba brevemente el motivo de la entrada o salida del equipo</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <div class="table-responsive scrollbar mb-3">
                        <label for="caracteristicas">Características<span class="text-danger"> *</span></label>
                        <table class="table table-striped overflow-hidden" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>Tipo de equipo</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Número de serie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
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
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <label>¿Este equipo pertenece a HO?<span class="text-danger"> *</span></label>
                                        <div>
                                            <input type="radio" class="form-check-input me-2" name="pertenece_a_ho_0" value="si" required>Si
                                            <input type="radio" class="form-check-input me-2" name="pertenece_a_ho_0" value="no" required>No
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-falcon-default btn-sm me-1 mb-1" onclick="addRow()">Agregar Fila</button>
                    </div>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <label for="evidencia">Evidencia<span class="text-danger"> *</span></label>
                    <form class="dropzone dropzone-multiple p-0" id="my-awesome-dropzone" data-dropzone=" action=" #!">
                        <div id="my-dropzone" class="dropzone">
                            <div class="dz-message">
                                <img src="Views/Resources/assets/img/icons/cloud-upload.svg" width="25" alt=""> <!-- Ícono local -->
                                <p>Arrastre y suelte archivos aquí.</p>
                            </div>
                        </div>
                    </form>
                    <p class="fs-10 mb-0">Cargue aquí las fotografías que sirven como evidencia. Máximo 5 fotografías. Formatos disponibles: jpg, jpeg, png. Límite máximo de 5MB por archivo</p>
                </div>
                <br>
                <div class="col-md-8 mx-auto">
                    <button type="button" class="btn btn-primary" onclick="prevStep()">Anterior</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
                </div>

            </div>

            <!-- Step 3 -->
            <div class="form-step col-md-8 mx-auto">
                <div class="mb-3">
                    <label for="nombre_responsable">Nombre de responsable<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="nombre_responsable" placeholder="Nombre de responsable" required>
                    <p class="fs-10 mb-0">Nombre de la persona que hace la solicitud</p>
                </div>
                <br>
                <div class="mb-3">
                    <label for="firma_de_responsable">Firma de responsable<span class="text-danger"> *</span></label>
                    <textarea id="firma_de_responsable" class="form-control" rows="5" placeholder="" readonly></textarea>
                    <p class="fs-10 mb-0">Firma de la persona que hace la solicitud</p>
                </div>
                <br>
                <div class="mb-3">
                    <label for="aceptacionRes">Aceptación de responsabilidad<span class="text-danger"> *</span></label>
                    <div class="d-flex">
                        <input type="checkbox" class="form-check-input me-2" id="aceptacionRes" required>
                        <p class="fs-10 mb-0" id="avisoText">
                            Al firmar esta solicitud, me comprometo a asumir la responsabilidad del cuidado y uso
                            de los equipos, siguiendo las políticas y procedimientos establecidos por la empresa.
                        </p>
                    </div>
                </div>
                <br>
                <div class="mb-3">
                    <label for="nombre_aut_ho">Nombre de quien autoriza en HO <span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="nombre_aut_ho" placeholder="Nombre de quien autoriza en HO" required>
                    <p class="fs-10 mb-0">Nombre de la persona que autoriza esta solicitud</p>
                </div>
                <br>
                <div class="mb-3">
                    <label for="correo_aut_ho">Correo electrónico de quien autoriza en HO <span class="text-danger"> *</span></label>
                    <input type="email" class="form-control" id="correo_aut_ho" placeholder="Correo electrónico de quien autoriza en HO" required>
                    <p class="fs-10 mb-0">Correo electrónico de la persona que autoriza esta solicitud</p>
                </div>
                <br>
                <div class="mb-3">
                    <label for="firma_aut_ho">Firma de quien autoriza en HO<span class="text-danger"> *</span></label>
                    <textarea id="firma_aut_ho" class="form-control" rows="5" placeholder="" readonly></textarea>
                    <p class="fs-10 mb-0">Firma de la persona que autoriza esta solicitud</p>
                </div>
                <br>
                <button type="button" class="btn btn-primary" onclick="prevStep()">Anterior</button>
                <button type="button" class="btn btn-primary" onclick="showPreview()">Verificar respuestas</button>
            </div>
        </form>

        <div class="table-responsive scrollbar" id="previewSection" style="display: none;">
            <h5 class="text-600">Vista previa de respuestas</h5>
            <br>
            <table class="table table-bordered overflow-hidden">
                <colgroup>
                    <col class="bg-primary-subtle" />
                    <col />
                    <col />
                </colgroup>
                <thead>
                    <tr class="btn-reveal-trigger">
                        <th scope="col">Campo</th>
                        <th scope="col">Respuesta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong class="text-primary">Fecha de solicitud:</strong></td>
                        <td><span id="preview_fecha_de_solicitud" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Nombre de la empresa:</strong></td>
                        <td><span id="preview_nombre_empresa" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Nombre de visitante:</strong></td>
                        <td><span id="preview_nombre_visitante" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Correo electrónico:</strong></td>
                        <td><span id="preview_correo" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Número de teléfono:</strong></td>
                        <td><span id="preview_telefono" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Folio:</strong></td>
                        <td><span id="preview_folio" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Propiedad del equipo:</strong></td>
                        <td><span id="preview_prioridad" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">¿Entrada o salida?</strong></td>
                        <td><span id="preview_entrada_o_salida" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Fecha de devolución:</strong></td>
                        <td><span id="preview_fecha_devolucion" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Motivo:</strong></td>
                        <td><span id="preview_fines_utilizacion" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Características:</strong></td>
                        <td>
                            <div class="table-responsive scrollbar">
                                <table class="table table-bordered overflow-hidden" id="preview_dynamicTable">
                                    <colgroup>
                                        <col class="bg-primary-subtle" />
                                        <col />
                                        <col />
                                        <col />
                                        <col />
                                    </colgroup>
                                    <thead>
                                        <tr class="btn-reveal-trigger">
                                            <th scope="col">Tipo de equipo</th>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Modelo</th>
                                            <th scope="col">Número de serie</th>
                                            <th class="text-end" scope="col">¿Este equipo pertenece a HO?</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Evidencia:</strong></td>
                        <td><img id="preview_evidencia" style="display:none; max-width:100%; height:auto;"></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Nombre de responsable:</strong></td>
                        <td><span id="preview_nombre_responsable" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Firma de responsable:</strong></td>
                        <td><span id="preview_firma_de_responsable" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Aceptación de responsabilidad:</strong></td>
                        <td><span id="preview_aceptacion_de_responsabilidad" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Nombre de quien autoriza en HO:</strong></td>
                        <td><span id="preview_nombre_aut_ho" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Correo electrónico de quien autoriza en HO:</strong></td>
                        <td><span id="preview_correo_aut_ho" class="text-muted"></span></td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">Firma de quien autoriza en HO:</strong></td>
                        <td><span id="preview_firma_aut_ho" class="text-muted"></span></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <button type="button" class="btn btn-primary" onclick="hidePreview()">Anterior</button>
            <button type="button" class="btn btn-primary" onclick="submitForm()">Enviar formulario</button>
        </div>


    </div>

</div>

<!-- JavaScript Files -->
<script defer src="Views/Resources/js/entrada_salida_de_equipo_visitantes.js"></script>
<script src="Views/Resources/vendors/dropzone/dropzone-min.js"></script>

<script>
    Dropzone.autoDiscover = false;

    const myDropzone = new Dropzone("#my-dropzone", {
        url: "#", // No necesita URL de carga ya que se maneja solo en frontend
        addRemoveLinks: true, // Añade enlaces para eliminar archivos
        dictRemoveFile: 'Remove',
        maxFilesize: 5, // Tamaño máximo del archivo en MB
        parallelUploads: 10, // Número máximo de archivos que se pueden cargar al mismo tiempo
        autoProcessQueue: false, // No procesar automáticamente ya que no interactuamos con el servidor
        init: function() {
            this.on("addedfile", function(file) {
                console.log("Archivo añadido: " + file.name);
            });

            this.on("removedfile", function(file) {
                console.log("Archivo eliminado: " + file.name);

                // Eliminar archivo de la lista interna de Dropzone
                let fileIndex = this.files.indexOf(file);
                if (fileIndex > -1) {
                    this.files.splice(fileIndex, 1);
                }
            });
        }
    });
</script>

<br>
<br>