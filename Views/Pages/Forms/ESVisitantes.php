<!-- <link rel="stylesheet" href="Views/Resources/css/catalogo_de_colaboradores.css"> -->

<!-- Contenido principal -->
<div class="col-md-6 offset-md-3"> <!--<div class="col-12 order-xxl-3"> -->
    <header class="mb-4">
        <h1>F-GES-05 ENTRADA/SALIDA DE EQUIPO DE CÓMPUTO (VISITANTES) REV. 01</h1>
        <h5>Formato de captura para el control de Entrada y Salida de equipo de cómputo y/o electrónicos, para visitantes</h5>
    </header>
    <!-- Main Content -->

    <div class="main-content">

        <form id="form2" onsubmit="event.preventDefault(); showPreview();">
            <!-- Step 1 -->
            <div class="form-step form-step-active">
                <div class="form-group">
                    <label for="fecha_de_solicitud">Fecha de solicitud *</label>
                    <input class="form-control datetimepicker" type="text" id="fecha_de_solicitud" name="fecha_de_solicitud" readonly>
                </div>
                <div class="form-group">
                    <label for="nombre_empresa">Nombre de la empresa*</label>
                    <input type="text" class="form-control" id="nombre_empresa" placeholder="Nombre de la empresa" required>
                </div>
                <div class="form-group">
                    <label for="nombre_visitante">Nombre de visitante*</label>
                    <input type="text" class="form-control" id="nombre_visitante" placeholder="Nombre de visitante" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico*</label>
                    <input type="email" class="form-control" id="correo" placeholder="Correo electrónico" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Número de teléfono*</label>
                    <input type="tel" class="form-control" id="telefono" placeholder="Número de teléfono" required>
                </div>
                <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
            </div>

            <!-- Step 2 -->
            <div class="form-step">
                <div class="form-group">
                    <label for="folio">Folio*</label>
                    <input type="text" class="form-control" id="folio" placeholder="Folio" required readonly>
                </div>
                <div class="form-group">
                    <label for="prioridad">Propiedad del equipo*</label>
                    <select class="form-control" id="prioridad" required>
                        <option>Harinera de Oriente</option>
                        <option>Uso personal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="entrada_o_salida">¿Entrada o salida?*</label>
                    <select class="form-control" id="entrada_o_salida" required>
                        <option>Entrada</option>
                        <option>Salida</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_devolucion">Fecha de devolucion</label>
                    <input class="form-control datetimepicker" id="fecha_devolucion" name="fecha_devolucion" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' type="date">
                </div>
                <div class="form-group">
                    <label for="fines_utilizacion">Motivo *</label>
                    <textarea class="form-control" rows="6" cols="50" id="fines_utilizacion" placeholder="Escribe aquí..." style="resize: none;"></textarea>
                </div>
                <div class="form-group">
                    <label for="caracteristicas">Características *</label>
                    <button type="button" onclick="addRow()">Agregar Fila</button>
                    <br><br>
                    <table id="dynamicTable">
                        <thead>
                            <tr>
                                <th>Tipo de equipo</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Número de serie</th>
                                <th>¿Este equipo pertenece a HO? *</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="tipo_equipo">
                                        <option value="Laptop">Laptop</option>
                                        <option value="PC de escritorio">PC de escritorio</option>
                                        <option value="Tableta">Tableta</option>
                                        <option value="Monitor/Pantalla/Proyector">Monitor/Pantalla/Proyector</option>
                                        <option value="Equipos de impresión y multifuncionales">Equipos de impresión y multifuncionales</option>
                                        <option value="Hardware (teclado/mouse/videocámaras/adaptadores)">Hardware (teclado/mouse/videocámaras/adaptadores)</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </td>
                                <td><input type="text" name="marca"></td>
                                <td><input type="text" name="modelo"></td>
                                <td><input type="text" name="numero_serie"></td>
                                <td><input type="checkbox" name="pertenece_a_ho" value="si"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label for="evidencia">Evidencia*</label>
                    <form action="upload.php" class="dropzone dropzone-multiple" id="my-awesome-dropzone">
                        <div class="dz-message" data-dz-message>
                            <img class="me-2" src="../assets/img/icons/cloud-upload.svg" width="25" alt="" />
                            Suelta tus archivos aquí o haz clic para seleccionar.
                        </div>
                        <div class="fallback">
                            <input name="file" type="file" multiple />
                        </div>
                        <div class="dz-preview dz-preview-multiple"></div> <!-- Aquí se mostrarán las vistas previas de los archivos -->
                    </form>
                </div>
                <button type="button" class="btn btn-primary" onclick="prevStep()">Anterior</button>
                <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
            </div>

            <!-- Step 3 -->
            <div class="form-step">
                <div class="form-group">
                    <label for="nombre_responsable">Nombre de responsable *</label>
                    <input type="text" class="form-control" id="nombre_responsable" placeholder="Nombre de responsable" required>
                </div>
                <div class="form-group">
                    <label for="firma_de_responsable">Firma de responsable*</label>
                    <textarea id="firma_de_responsable" class="form-control" rows="5" placeholder="" readonly></textarea>
                </div>

                <div class="form-group">
                    <input type="checkbox" class="form-check-input" id="aceptación_de_responsabilidad" required>
                    <label for="aceptar">Aceptación de responsabilidad *</label>
                    <label for="responsabilidad" id="responsabilidad">Al firmar esta solicitud, me comprometo a asumir la responsabilidad del cuidado y uso
                        de los equipos, siguiendo las políticas y procedimientos establecidos por la empresa. * </label>
                </div>
                <div class="form-group">
                    <label for="nombre_aut_ho">Nombre de quien autoriza en HO *</label>
                    <input type="text" class="form-control" id="nombre_aut_ho" placeholder="Nombre de quien autoriza en HO" required>
                </div>
                <div class="form-group">
                    <label for="correo_aut_ho">Correo electrónico de quien autoriza en HO *</label>
                    <input type="email" class="form-control" id="correo_aut_ho" placeholder="Correo electrónico de quien autoriza en HO" required>
                </div>
                <div class="form-group">
                    <label for="firma_aut_ho">Firma de quien autoriza en HO*</label>
                    <textarea id="firma_aut_ho" class="form-control" rows="5" placeholder="" readonly></textarea>
                </div>

                <button type="button" class="btn btn-primary" onclick="prevStep()">Anterior</button>
                <button type="button" class="btn btn-primary" onclick="showPreview()">Verificar respuestas</button>
            </div>


            <div id="previewSection" style="display: none;">
                <h2>Vista previa del formulario</h2>

                <!-- Pregunta 1: Fecha de solicitud -->
                <div class="form-group">
                    <label for="preview_fecha_de_solicitud">Fecha de solicitud:</label>
                    <p id="preview_fecha_de_solicitud"></p>
                </div>

                <!-- Pregunta 2: Nombre de la empresa -->
                <div class="form-group">
                    <label for="preview_nombre_empresa">Nombre de la empresa:</label>
                    <p id="preview_nombre_empresa"></p>
                </div>

                <!-- Pregunta 3: Nombre de visitante -->
                <div class="form-group">
                    <label for="preview_nombre_visitante">Nombre de visitante:</label>
                    <p id="preview_nombre_visitante"></p>
                </div>

                <!-- Pregunta 4: Correo electrónico -->
                <div class="form-group">
                    <label for="preview_correo">Correo electrónico:</label>
                    <p id="preview_correo"></p>
                </div>

                <!-- Pregunta 5: Número de teléfono -->
                <div class="form-group">
                    <label for="preview_telefono">Número de teléfono:</label>
                    <p id="preview_telefono"></p>
                </div>

                <!-- Pregunta 6: Folio -->
                <div class="form-group">
                    <label for="preview_folio">Folio:</label>
                    <p id="preview_folio"></p>
                </div>

                <!-- Pregunta 7: Propiedad del equipo -->
                <div class="form-group">
                    <label for="preview_prioridad">Propiedad del equipo:</label>
                    <p id="preview_prioridad"></p>
                </div>

                <!-- Pregunta 8: Entrada o salida -->
                <div class="form-group">
                    <label for="preview_entrada_o_salida">¿Entrada o salida?</label>
                    <p id="preview_entrada_o_salida"></p>
                </div>

                <!-- Pregunta 9: Fecha de devolución -->
                <div class="form-group">
                    <label for="preview_fecha_devolucion">Fecha de devolución:</label>
                    <p id="preview_fecha_devolucion"></p>
                </div>

                <!-- Pregunta 10: Motivo -->
                <div class="form-group">
                    <label for="preview_fines_utilizacion">Motivo:</label>
                    <p id="preview_fines_utilizacion"></p>
                </div>

                <!-- Pregunta 11: Características -->
                <div class="form-group">
                    <label for="preview_caracteristicas">Características:</label>
                    <table id="preview_caracteristicas"></table>
                </div>

                <!-- Pregunta 12: Evidencia -->
                <div class="form-group">
                    <label for="preview_evidencia">Evidencia:</label>
                    <img id="preview_evidencia" style="display:none; max-width:100%; height:auto;">
                </div>


                <!-- Pregunta 13: Nombre de responsable -->
                <div class="form-group">
                    <label for="preview_nombre_responsable">Nombre de responsable:</label>
                    <p id="preview_nombre_responsable"></p>
                </div>

                <!-- Pregunta 14: Firma de responsable -->
                <div class="form-group">
                    <label for="preview_firma_de_responsable">Firma de responsable:</label>
                    <p id="preview_firma_de_responsable"></p>
                </div>

                <!-- Pregunta 15: Aceptación de responsabilidad -->
                <div class="form-group">
                    <label for="preview_aceptacion_de_responsabilidad">Aceptación de responsabilidad:</label>
                    <p id="preview_aceptacion_de_responsabilidad"></p>
                </div>

                <!-- Pregunta 16: Nombre de quien autoriza en HO -->
                <div class="form-group">
                    <label for="preview_nombre_aut_ho">Nombre de quien autoriza en HO:</label>
                    <p id="preview_nombre_aut_ho"></p>
                </div>

                <!-- Pregunta 17: Correo electrónico de quien autoriza en HO -->
                <div class="form-group">
                    <label for="preview_correo_aut_ho">Correo electrónico de quien autoriza en HO:</label>
                    <p id="preview_correo_aut_ho"></p>
                </div>

                <!-- Pregunta 18: Firma de quien autoriza en HO -->
                <div class="form-group">
                    <label for="preview_firma_aut_ho">Firma de quien autoriza en HO:</label>
                    <p id="preview_firma_aut_ho"></p>
                </div>


                <button type="button" class="btn btn-primary" onclick="prevStep()">Anterior</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Enviar formulario</button>

            </div>
        </form>
    </div>

    <!-- JavaScript Files -->
    <script defer src="Views/Resources/js/entrada_salida_de_equipo_visitantes.js"></script>
    <script src="Views/Resources/vendors/dropzone/dropzone-min.js"></script>