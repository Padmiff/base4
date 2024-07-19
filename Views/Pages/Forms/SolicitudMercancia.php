<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<head>
    <link rel="stylesheet" href="Views/Resources/css/catalogo_de_colaboradores.css">
    <!-- Contenido principal -->
</head>



<div class="card mb-3">
    <div class="card-header z-1">
        <header class="">
            <h1 class="text-primary">F-CMP-01 Solicitud de mercancía o servicio Rev. 02</h1>
            <h5 class="text-600">Formulario de requisición</h5>
        </header>
    </div>
    <div class="card-body">


        <form id="form2" onsubmit="event.preventDefault(); showPreview();">
            <div class="form-step form-step-active">
                <div class="mb-3">
                    <label for="N_colaborador">N de colaborador<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="N_colaborador" name="N_colaborador" placeholder="N de colaborador" value="<?php echo isset($_SESSION['N_colaborador']) ? $_SESSION['N_colaborador'] : ''; ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="departamento">Departamento<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="departamento" name="departamento" placeholder="Departamento" value="<?php echo isset($_SESSION['departamento']) ? $_SESSION['departamento'] : ''; ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="N_empleado">Nombre de colaborador<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="N_empleado" name="N_empleado" placeholder="Nombre de colaborador" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="Ape_paterno">Apellido paterno<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="Ape_paterno" name="Ape_paterno" placeholder="Apellido paterno" value="<?php echo isset($_SESSION['Ape_paterno']) ? $_SESSION['Ape_paterno'] : ''; ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="Ape_materno">Apellido materno<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="Ape_materno" name="Ape_materno" placeholder="Apellido materno" value="<?php echo isset($_SESSION['Ape_materno']) ? $_SESSION['Ape_materno'] : ''; ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="correo">Correo electrónico<span class="text-danger"> *</span></label>
                    <input type="email" class="form-control form-control-sm" id="correo" name="correo" placeholder="Correo electrónico" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="telefono">Número de teléfono<span class="text-danger"> *</span></label>
                    <input type="tel" class="form-control form-control-sm" id="telefono" name="telefono" placeholder="Número de teléfono" value="<?php echo isset($_SESSION['telefono']) ? $_SESSION['telefono'] : ''; ?>" required readonly>
                </div>
                <br>
                <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
            </div>

            <!-- Step 2 -->
            <div class="form-step">
                <div class="mb-3">
                    <label for="Folio">Folio<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="folio" name="folio" placeholder="Folio" readonly required>
                </div>
                <div class="mb-3">
                    <label for="Prioridad">Prioridad de atención de la solicitud<span class="text-danger"> *</span></label>
                    <select class="form-select form-select-sm" id="Prioridad" name="Prioridad" required>
                        <option>--Seleccione--</option>
                        <option>Programado</option>
                        <option>Urgente</option>
                    </select>
                    <p class="fs-10 mb-0">Seleccione el tipo de atención de la solicitud</p>
                </div>

                <div class="mb-3">
                    <label for="gestiona">¿Qué departamento gestionará la compra?<span class="text-danger"> *</span></label>
                    <select class="form-select form-select-sm" id="gestiona" name="gestiona" required>
                        <option>--Seleccione--</option>
                        <option>Compras (Departamento de compras)</option>
                        <option>Directa (Departamento que hace la solicitud)</option>
                    </select>
                    <p class="fs-10 mb-0">Seleccione el departamento que gestionará la compra</p>
                </div>
                <div class="mb-3">
                    <label for="solicitando"">¿Qué  se está solicitando?<span class=" text-danger"> *</span></label>
                    <select class="form-select form-select-sm" id="solicitando" name="solicitando" required>
                        <option>--Seleccione--</option>
                        <option>Donativo</option>
                        <option>Material</option>
                        <option>Servicio</option>
                    </select>
                    <p class="fs-10 mb-0">Selecciona el tipo solicitud</p>
                </div>
                <div class="mb-3">
                    <label for="fecha_pedido">Fecha de pedido<span class="text-danger"> *</span></label>
                    <input class="form-control datetimepicker form-control form-control-sm" type="text" id="fecha_pedido" name="fecha_pedido" readonly>
                    <p class="fs-10 mb-0">Fecha y hora en la que se realiza la solicitud</p>
                </div>
                <div class="mb-3">
                    <label for="fecha_entrega">Fecha de entrega<span class="text-danger"> *</span></label>
                    <input class="form-control datetimepicker form-control form-control-sm" id="fecha_entrega" name="fecha_entrega" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' />
                    <p class="fs-10 mb-0">Fecha en la que se atiende la solicitud</p>
                </div>
                <div class="mb-3">
                    <label for="mercancias_servicios">Mercancías y/o Servicios<span class="text-danger"> *</span></label>
                    <br>
                    <div class="table-responsive scrollbar">
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

                    <br>
                    <button class="btn btn-falcon-default btn-sm me-1 mb-1" type="button" onclick="addRow()"> + Agregar Fila</button>
                </div>
                <div class="mb-3">
                    <label for="fines_utilizacion">Fines de utilización<span class="text-danger"> *</span></label>
                    <textarea class="form-control" rows="6" cols="50" id="fines_utilizacion" placeholder="" style="resize: none;" name="fines_utilizacion"></textarea>
                    <p class="fs-10 mb-0">Describa la finalidad de utilización de esta mercancía y/o servicios</p>
                </div>
                <br>
                <button type="button" class="btn btn-primary" onclick="prevStep()">Anterior</button>
                <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
            </div>

            <!-- Step 3 -->
            <div class="form-step">
                <div class="mb-3">
                    <label for="nombre_solicitud">Nombre de la persona que hace la solicitud<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="nombre_solicitud" name="nombre_solicitud" placeholder="Nombre de la persona que hace la solicitud" required>
                    <p class="fs-10 mb-0">Nombre de la persona que hace la solicitud</p>
                </div>
                <div class="mb-3">
                    <label for="firma_solicitud">Firma de la persona que hace la solicitud<span class="text-danger"> *</span></label>
                    <textarea id="firma_solicitud" name="firma_solicitud" class="form-control form-control-sm" rows="5" placeholder="" readonly></textarea>
                    <p class="fs-10 mb-0">Firma de la persona que hace la solicitud</p>
                </div>

                <div class="mb-3">
                    <label for="nombre_jefe">Nombre del Jefe inmediato de la persona que hace la solicitud<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="nombre_jefe" name="nombre_jefe" placeholder="Nombre del Jefe inmediato de la persona que hace la solicitud" required>
                    <p class="fs-10 mb-0">Nombre del Jefe inmediato del solicitante</p>
                </div>
                <div class="mb-3">
                    <label for="correo_jefe">Correo electrónico del Jefe inmediato de la persona que hace la solicitud<span class="text-danger"> *</span></label>
                    <input type="email" class="form-control form-control-sm" id="correo_jefe" name="correo_jefe" placeholder="Correo electrónico del Jefe inmediato de la persona que hace la solicitud" required>
                    <p class="fs-10 mb-0">Introduzca el correo electrónico del Jefe inmediato de la persona que hace la solicitud</p>
                </div>
                <div class="mb-3">
                    <label for="firma_recibe">Firma del Jefe inmediato de la persona que hace la solicitud<span class="text-danger"> *</span></label>
                    <textarea id="firma_jefe_recibe" name="firma_jefe_recibe" class="form-control form-control-sm" rows="5" placeholder="" readonly></textarea>
                    <p class="fs-10 mb-0">Firma del Jefe inmediato de la persona que hace la solicitud</p>
                </div>
                <div class="mb-3">
                    <label for="nombre_recibe">Nombre de quién recibe la solicitud en el Departamento de compras<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control form-control-sm" id="nombre_recibe" name="nombre_recibe" placeholder="Nombre de quién recibe la solicitud en el Departamento de compras" required>
                    <p class="fs-10 mb-0">Nombre de la persona que recibe la solicitud</p>
                </div>
                <div class="mb-3">
                    <label for="firma_recibe">Firma de quién recibe la solicitud en el Departamento de compras<span class="text-danger"> *</span></label>
                    <textarea id="firma_recibe" name="firma_recibe" class="form-control form-control-sm" rows="5" placeholder="" readonly></textarea>
                    <p class="fs-10 mb-0">Firma de la persona que recibe la solicitud en el Departamento de compras</p>
                </div>
                <br>
                <button type="button" id="prevButton" class="btn btn-primary" onclick="prevStep()">Anterior</button>
                <button type="button" id="verifyButton'" class="btn btn-primary" onclick="validateAndShowPreview()">Verificar respuestas</button>
            </div>

            <!-- Preview Section -->

            <div id="preview" style="display: none;">
                <h2>Vista previa de respuestas</h2>
                <p><strong class="text-primary">N de colaborador: <span id="preview_N_colaborador" class="text-muted"></span></p>
                <p><strong class="text-primary">Departamento: <span id="preview_departamento" class="text-muted"></span></p>
                <p><strong class="text-primary">Nombre de colaborador: <span id="preview_N_empleado" class="text-muted"></span></p>
                <p><strong class="text-primary">Apellido paterno: <span id="preview_Ape_paterno" class="text-muted"></span></p>
                <p>
                <p><strong class="text-primary">Apellido materno: <span id="preview_Ape_materno" class="text-muted"></span></p>
                <p><strong class="text-primary">Correo electrónico: <span id="preview_correo" class="text-muted"></span></p>
                <p><strong class="text-primary">Número de teléfono: <span id="preview_telefono" class="text-muted"></span></p>
                <p><strong class="text-primary">Folio: <span id="preview_folio" class="text-muted"></span></p>
                <p><strong class="text-primary">Prioridad: <span id="preview_Prioridad" class="text-muted"></span></p>
                <p><strong class="text-primary">Departamento que gestionará la compra: <span id="preview_gestiona" class="text-muted"></span></p>
                <p><strong class="text-primary">Solicitando: <span id="preview_solicitando" class="text-muted"></span></p>
                <p><strong class="text-primary">Fecha de pedido: <span id="preview_fecha_pedido" class="text-muted"></span></p>
                <p><strong class="text-primary">>Fecha de entrega: <span id="preview_fecha_entrega" class="text-muted"></span></p>
                <p><strong class="text-primary">Mercancías y/o Servicios: <span id="preview_dynamicTable" class="text-muted"></span></p>

                <p><strong class="text-primary">Fines de utilización: <span id="preview_fines_utilizacion" class="text-muted"></span></p>
                <p><strong class="text-primary">Nombre de la persona que hace la solicitud: <span id="preview_nombre_solicitud" class="text-muted"></span></p>
                <p><strong class="text-primary">Firma de la persona que hace la solicitud: <span id="preview_firma_solicitud" class="text-muted"></span></p>
                <p><strong class="text-primary">Nombre del Jefe inmediato: <span id="preview_nombre_jefe" class="text-muted"></span></p>
                <p><strong class="text-primary">Correo electrónico del Jefe inmediato: <span id="preview_correo_jefe" class="text-muted"></span></p>
                <p><strong class="text-primary">Firma del Jefe inmediato de la persona que hace la solicitud: <span id="preview_firma_jefe_recibe" class="text-muted"></span></p>
                <p><strong class="text-primary">Nombre de quién recibe la solicitud en el Departamento de compras: <span id="preview_nombre_recibe" class="text-muted"></span></p>
                <p><strong class="text-primary">Firma de quién recibe la solicitud en el Departamento de compras: <span id="preview_firma_recibe" class="text-muted"></span></p>
                <button type="button" class="btn btn-secondary" onclick="editForm()">Editar respuestas</button>
                <button type="submit" class="btn btn-success" onclick="submitForm()">Enviar formulario</button>

            </div>
    </div>
    <br>
    <br>
    <br>
    <script>
        function validateForm() {
            var isValid = true;
            var errorMessages = [];
            var fields = [
                'Prioridad',
                'gestiona',
                'solicitando',
                'fecha_entrega',
                'fines_utilizacion',
                'nombre_solicitud',
                'nombre_jefe',
                'correo_jefe',
                'nombre_recibe'

            ];

            fields.forEach(function(field) {
                var element = document.getElementById(field);
                if (!element) {
                    isValid = false;
                    errorMessages.push('El campo con ID "' + field + '" no se encuentra en el formulario.');
                    console.log('Elemento no encontrado:', field);
                } else {
                    console.log('Validando campo:', field, 'Valor:', element.value.trim());
                    if (!element.value.trim() && element.type !== 'textarea') {
                        isValid = false;
                        errorMessages.push('Por favor, llene el campo ' + element.placeholder + '.');
                    }
                }
            });

            if (!isValid) {
                alert(errorMessages.join('\n'));
            }

            return isValid;
        }
    </script>

    <script defer src="Views/Resources/js/formulario2.js"></script>