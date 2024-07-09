<!-- <link rel="stylesheet" href="Views/Resources/css/catalogo_de_colaboradores.css"> -->
<!-- Contenido principal -->
<div class="col-md-6 offset-md-3"> <!--<div class="col-12 order-xxl-3"> -->
    <header class="mb-4 mt-4">
        <h1>Solicitud de mercancia o servicio</h1>
    </header>

    <form id="form2" onsubmit="event.preventDefault(); showPreview();">
        <div class="form-step form-step-active">
            <div class="form-group">
                <label for="N_colaborador">N de colaborador*</label>
                <input type="text" class="form-control" id="N_colaborador" placeholder="N de colaborador" required>
                <br>
                <button type="button" class="btn btn-primary" onclick="">Verificar</button>
            </div>

            <div class="form-group">
                <label for="departamento">Departamento*</label>
                <select class="form-select" id="departamento" required>
                    <option value="">Selecciona un departamento</option>
                    <!-- Opciones de departamento se cargarán dinámicamente desde JavaScript -->
                </select>
            </div>
            <div class="form-group">
                <label for="N_empleado">Nombre de colaborador*</label>
                <input type="text" class="form-control" id="N_empleado" placeholder="Nombre de colaborador" required>
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
                <label for="Folio">Folio*</label>
                <input type="text" class="form-control" id="Folio" placeholder="Folio" required>
            </div>
            <div class="form-group">
                <label for="Prioridad">Prioridad de atención de la solicitud*</label>
                <select class="form-select" id="Prioridad" required>
                    <option>Programado</option>
                    <option>Urgente</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gestiona">¿Qué departamento gestionará la compra?*</label>
                <select class="form-control" id="gestiona" required>
                    <option>Compras (Departamento de compras)</option>
                    <option>Directa (Departamento que hace la solicitud)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_pedido">Fecha de pedido*</label>
                <input class="form-control datetimepicker" type="text" id="fecha_pedido" name="fecha_pedido" readonly>
            </div>
            <div class="form-group">
                <label for="fecha_entrega">Fecha de entrega*</label>
                <input class="form-control datetimepicker" id="fecha_entrega" name="fecha_entrega" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' />
            </div>
            <div class="form-group">
                <label for="mercancias_servicios">Mercancías y/o Servicios*</label>
                <button class="btn btn-falcon-default btn-sm me-1 mb-1" type="button" onclick="addRow()"> + Agregar Fila</button>
                <br><br>
                <table id="dynamicTable" style="display:none;">
                    <thead>
                        <tr>
                            <th>Partida</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <label for="fines_utilizacion">Fines de utilización*</label>
                <textarea class="form-control" rows="6" cols="50" id="fines_utilizacion" placeholder="Escribe aquí..." style="resize: none;"></textarea>
            </div>
            <button type="button" class="btn btn-primary" onclick="prevStep()">Anterior</button>
            <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
        </div>

        <!-- Step 3 -->
        <div class="form-step">
            <div class="form-group">
                <label for="nombre_solicitud">Nombre de la persona que hace la solicitud*</label>
                <input type="text" class="form-control" id="nombre_solicitud" placeholder="Nombre de la persona que hace la solicitud" required>
            </div>
            <div class="form-group">
                <label for="firma_solicitud">Firma de la persona que hace la solicitud*</label>
                <textarea id="firma_solicitud" class="form-control" rows="5" placeholder="" readonly></textarea>
            </div>

            <div class="form-group">
                <label for="nombre_jefe">Nombre del Jefe inmediato de la persona que hace la solicitud*</label>
                <input type="text" class="form-control" id="nombre_jefe" placeholder="Nombre del Jefe inmediato de la persona que hace la solicitud" required>
            </div>
            <div class="form-group">
                <label for="correo_jefe">Correo electrónico del Jefe inmediato de la persona que hace la solicitud*</label>
                <input type="email" class="form-control" id="correo_jefe" placeholder="Correo electrónico del Jefe inmediato de la persona que hace la solicitud" required>
            </div>
            <div class="form-group">
                <label for="nombre_recibe">Nombre de quién recibe la solicitud en el Departamento de compras*</label>
                <input type="text" class="form-control" id="nombre_recibe" placeholder="Nombre de quién recibe la solicitud en el Departamento de compras" required>
            </div>
            <div class="form-group">
                <label for="firma_recibe">Firma de quién recibe la solicitud en el Departamento de compras*</label>
                <textarea id="firma_recibe" class="form-control" rows="5" placeholder="" readonly></textarea>
            </div>

            <button type="button" id="prevButton" class="btn btn-primary" onclick="prevStep()">Anterior</button>
            <button type="button" id="verifyButton'" class="btn btn-primary" onclick="showPreview()">Verificar respuestas</button>
        </div>

        <!-- Preview Section -->

        <div id="preview" style="display: none;">
            <h2>Vista previa de respuestas</h2>
            <p>N de colaborador: <span id="preview_N_colaborador"></span></p>
            <p>Departamento: <span id="preview_departamento"></span></p>
            <p>Nombre de colaborador: <span id="preview_N_empleado"></span></p>
            <p>Correo electrónico: <span id="preview_correo"></span></p>
            <p>Número de teléfono: <span id="preview_telefono"></span></p>
            <p>Folio: <span id="preview_Folio"></span></p>
            <p>Prioridad: <span id="preview_Prioridad"></span></p>
            <p>Departamento que gestionará la compra: <span id="preview_gestiona"></span></p>
            <p>Fecha de pedido: <span id="preview_fecha_pedido"></span></p>
            <p>Fecha de entrega: <span id="preview_fecha_entrega"></span></p>
            <table id="preview_dynamicTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Partida</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <p>Fines de utilización: <span id="preview_fines_utilizacion"></span></p>
            <p>Nombre de la persona que hace la solicitud: <span id="preview_nombre_solicitud"></span></p>
            <p>Firma de la persona que hace la solicitud: <span id="preview_firma_solicitud"></span></p>
            <p>Nombre del Jefe inmediato: <span id="preview_nombre_jefe"></span></p>
            <p>Correo electrónico del Jefe inmediato: <span id="preview_correo_jefe"></span></p>
            <p>Nombre de quién recibe la solicitud en el Departamento de compras: <span id="preview_nombre_recibe"></span></p>
            <p>Firma de quién recibe la solicitud en el Departamento de compras: <span id="preview_firma_recibe"></span></p>
            <button type="button" class="btn btn-secondary" onclick="editForm()">Editar respuestas</button>
            <button type="submit" class="btn btn-success" onclick="submitForm()">Enviar formulario</button>

        </div>




        <script defer src="Views/Resources/js/formulario2.js"></script>