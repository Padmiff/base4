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
    <link href="Views/Resources/vendors/dropzone/dropzone.css" rel="stylesheet" />
    <!-- Contenido principal -->
</head>

<div>
    <header class="">
        <h1 class="text-primary">F-GES-08 Reporte de incidencias informáticas</h1>
        <h5 class="text-600">Formulario para el reporte de incidencias</h5>
    </header>
</div>

<div class="card mb-3">
    <div class="card-body bg-body-tertiary">


        <form id="form2" onsubmit="event.preventDefault(); showPreview();">
            <div class="form-step form-step-active">
                <div class="form-group">
                    <label for="N_colaborador">N de colaborador<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="N_colaborador" name="N_colaborador" placeholder="N de colaborador" value="<?php echo isset($_SESSION['N_colaborador']) ? $_SESSION['N_colaborador'] : ''; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label for="departamento">Departamento<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="departamento" name="departamento" placeholder="Departamento" value="<?php echo isset($_SESSION['departamento']) ? $_SESSION['departamento'] : ''; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label for="N_empleado">Nombre de colaborador<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="N_empleado" name="N_empleado" placeholder="Nombre de colaborador" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label for="Ape_paterno">Apellido paterno<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="Ape_paterno" name="Ape_paterno" placeholder="Apellido paterno" value="<?php echo isset($_SESSION['Ape_paterno']) ? $_SESSION['Ape_paterno'] : ''; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label for="Ape_materno">Apellido materno<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="Ape_materno" name="Ape_materno" placeholder="Apellido materno" value="<?php echo isset($_SESSION['Ape_materno']) ? $_SESSION['Ape_materno'] : ''; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label for="correo">Correo electrónico<span class="text-danger"> *</span></label>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required readonly>
                </div>

                <div class="form-group">
                    <label for="telefono">Número de teléfono<span class="text-danger"> *</span></label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Número de teléfono" value="<?php echo isset($_SESSION['telefono']) ? $_SESSION['telefono'] : ''; ?>" required readonly>
                </div>
                <br>
                <button type="button" class="btn btn-primary" onclick="nextStep()">Siguiente</button>
            </div>

            <!-- Step 2 -->
            <div class="form-step">
                <div class="form-group">
                    <label for="Folio">Folio<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="folio" name="folio" placeholder="Folio" readonly required>
                </div>
                <div class="form-group">
                    <label for="fecha_reporte">Fecha de reporte<span class="text-danger"> *</label>
                    <input class="form-control datetimepicker" type="text" id="fecha_reporte" name="fecha_reporte" readonly>
                </div>
                <div class="form-group">
                    <label for="tipo_inc">¿Qué tipo de incidencia reporta?<span class="text-danger"> *</span></label>
                    <select class="form-select" id="tipo_inc" required>
                        <option>Hardware (pantalla, impresora, disco duro ...)</option>
                        <option>Internet</option>
                        <option>Software (antivirus, office, correo electrónico ...)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="gestiona">¿En qué departamento o área de la organización se tiene esta incidencia?<span class="text-danger"> *</label>
                    <input type="text" class="form-control" id="dep_rep" placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="descrip_inc">Descripción de la incidencia *</label>
                    <textarea class="form-control" rows="6" cols="50" id="descrip_inc" placeholder="Escribe aquí..." style="resize: none;"></textarea>
                </div>
                <label for="evidencia">Evidencia*</label>
                <div class="dropzone dropzone-multiple p-0" data-dropzone="data-dropzone" data-options='{"url":"valid/url","maxFiles":null,"dictDefaultMessage":"Choose or Drop a file here"}'>
                    <div class="fallback"><input type="file" name="file" multiple /></div>
                    <div class="dz-preview dz-preview-single">
                        <div class="dz-preview-cover dz-complete">
                            <img class="dz-preview-img" src="Views/Resources/assets/img/generic/image-file-2.png" alt="..." data-dz-thumbnail="" />
                            <a class="dz-remove text-danger" href="#!" data-dz-remove="data-dz-remove"><span class="fas fa-times"></span></a>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                            <div class="dz-errormessage m-1"><span data-dz-errormessage="data-dz-errormessage"></span></div>
                        </div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                    </div>
                    <div class="dz-message" data-dz-message="data-dz-message">
                        <div class="dz-message-text">
                            <img class="me-2" src="Views/Resources/assets/img/icons/cloud-upload.svg" width="25" alt="" />Drop your file here
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fecha_atencion">Fecha de atención</label>
                    <input class="form-control datetimepicker" type="date" id="fecha_atencion" name="fecha_atencion" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' />
                </div>
                <div class="form-group">
                    <label for="firma_de_conformidad">Firma de conformidad*</label>
                    <textarea id="firma_de_conformidad" class="form-control" rows="5" placeholder="" readonly></textarea>
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
                <p>Apellido paterno: <span id="preview_Ape_paterno"></span></p>
                <p>Apellido materno: <span id="preview_Ape_materno"></span></p>
                <p>Correo electrónico: <span id="preview_correo"></span></p>
                <p>Número de teléfono: <span id="preview_telefono"></span></p>
                <p>Folio: <span id="preview_folio"></span></p>
                <p>Fecha reporte: <span id="preview_fecha_reporte"></span></p>
                <p>¿Qué tipo de incidencia reporta?: <span id="preview_tipo_inc"></span></p>
                <p>¿En qué departamento o área de la organización se tiene esta incidencia?: <span id="preview_dep_rep"></span></p>
                <p>Descripción de la incidencia: <span id="preview_descrip_inc"></span></p>
                <p>Fecha de atención: <span id="preview_fecha_atencion"></span></p>
                <p>Firma de conformidad: <span id="preview_firma_de_conformidad"></span></p>

            </div>
    </div>

    <br>
    <br>
    <br>


    <script defer src="Views/Resources/js/repInci.js"></script>
    <script src="Views/Resources/vendors/dropzone/dropzone-min.js"></script>
    <script src="Views/Resources/js/dropzone-config.js"></script>