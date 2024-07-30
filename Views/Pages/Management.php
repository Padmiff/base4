<?php
require_once 'Controllers/GraphicsController.php';
require_once 'Controllers/AuthenticationController.php'; // Incluye el controlador de conexiones

try {
    // Obtiene datos para gráficos desde el modelo de datos
    $datos = DatosModel::obtenerDatosGrafica(); // Datos para gráficos generales
    $datosInactivos = DatosModel::obtenerDatosGraficaInactivos(); // Datos sobre usuarios inactivos
    $datosIncidencias = DatosModel::obtenerDatosPorTipoIncidencia(); // Datos sobre tipos de incidencias
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}

try {
    // Obtener los datos de género para la intranet
    $datosGenero = GraphicsController::obtenerDatosGeneroIntranet();
    $totalMasculino = $datosGenero['TotalMasculinoIntranet']; // Total de hombres
    $totalFemenino = $datosGenero['TotalFemeninoIntranet']; // Total de mujeres

    // Calcular el total de individuos
    $totalIndividuos = $totalMasculino + $totalFemenino;

    // Calcular los porcentajes actuales
    $porcentajeMasculino = ($totalIndividuos > 0) ? ($totalMasculino / $totalIndividuos) * 100 : 0;
    $porcentajeFemenino = ($totalIndividuos > 0) ? ($totalFemenino / $totalIndividuos) * 100 : 0;
} catch (Exception $e) {
    echo 'Error al obtener los datos de género: ' . $e->getMessage();
    // Valores por defecto en caso de error
    $totalMasculino = 0;
    $totalFemenino = 0;
    $porcentajeMasculino = 0;
    $porcentajeFemenino = 0;
}

try {
    // Obtener las conexiones diarias
    $conexionesDiarias = AuthenticationController::obtenerConexionesDiarias();
} catch (Exception $e) {
    echo 'Error al obtener las conexiones diarias: ' . $e->getMessage();
    $conexionesDiarias = 0;
}
?>
<div class="col-lg-12">
    <div class="card overflow-hidden" style="min-width: 12rem">
        <div class="bg-holder bg-card" style="background-image:url(Views/Resources/assets/img/icons/spot-illustrations/corner-2.png);"></div>
        <!--/.bg-holder-->
        <div class="card-body position-relative">
            <h6>Conexiones Diarias<span class="badge badge-subtle-info rounded-pill ms-2"><?php echo number_format($conexionesDiarias); ?></span></h6>
            <div class="display-4 fs-5 mb-2 fw-normal font-sans-serif text-info"><?php echo $conexionesDiarias; ?></div>
        </div>
    </div>
</div>

<div class="col-xxl-6 col-lg-12">
    <div class="row g-3 mb-3">
        <div class="col-lg-12">
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <div class="card overflow-hidden" style="min-width: 12rem">
                        <div class="bg-holder bg-card" style="background-image:url(Views/Resources/assets/img/icons/spot-illustrations/corner-2.png);"></div>
                        <!--/.bg-holder-->
                        <div class="card-body position-relative">
                            <h6>Hombres en la intranet<span class="badge badge-subtle-info rounded-pill ms-2"><?php echo number_format($porcentajeMasculino, 2); ?>%</span></h6>
                            <div class="display-4 fs-5 mb-2 fw-normal font-sans-serif text-info"><?php echo $totalMasculino; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card overflow-hidden" style="min-width: 12rem">
                        <div class="bg-holder bg-card" style="background-image:url(Views/Resources/assets/img/icons/spot-illustrations/corner-1.png);"></div>
                        <!--/.bg-holder-->
                        <div class="card-body position-relative">
                            <h6>Mujeres en la intranet<span class="badge badge-subtle-warning rounded-pill ms-2"><?php echo number_format($porcentajeFemenino, 2); ?>%</span></h6>
                            <div class="display-4 fs-5 mb-2 fw-normal font-sans-serif text-warning"><?php echo $totalFemenino; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row flex-between-center">
                        <div class="col-auto">
                            <h6 class="mb-0">Reporte de usuarios bloqueados</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="usuariosinactivos" width="350" data-empleados-inactivos="<?php echo $datosInactivos['empleados']; ?>" data-clientes-inactivos="<?php echo $datosInactivos['clientes']; ?>" data-proveedores-inactivos="<?php echo $datosInactivos['proveedores']; ?>"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-xxl-6 col-lg-6 order-xxl-1">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <div class="row flex-between-center">
                        <div class="col-auto">
                            <h6 class="mb-0">Total de usuarios registrados</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="usuarios" width="350" data-empleados="<?php echo $datos['empleados']; ?>" data-clientes="<?php echo $datos['clientes']; ?>" data-proveedores="<?php echo $datos['proveedores']; ?>"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-lg-6">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <div class="row flex-between-center">
                        <div class="col-auto">
                            <h6 class="mb-0">Reporte incidencias</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="incidencias" data-hardware="<?= $datosIncidencias['hardware'] ?>" data-internet="<?= $datosIncidencias['internet'] ?>" data-software="<?= $datosIncidencias['software'] ?>">
                    </canvas>
                </div>
            </div>
        </div>
    </div>
</div>







<div class="modal fade" id="eventDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border"></div>
    </div>
</div>
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border">
            <form id="addEventForm" autocomplete="off">
                <div class="modal-header px-x1 bg-body-tertiary border-bottom-0">
                    <h5 class="modal-title">Create Schedule</h5>
                    <button class="btn-close me-n1" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-x1">
                    <div class="mb-3">
                        <label class="fs-9" for="eventTitle">Title</label>
                        <input class="form-control" id="eventTitle" type="text" name="title" required="required" />
                    </div>
                    <div class="mb-3">
                        <label class="fs-9" for="eventStartDate">Start Date</label>
                        <input class="form-control datetimepicker" id="eventStartDate" type="text" required="required" name="startDate" placeholder="yyyy/mm/dd hh:mm" data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}' />
                    </div>
                    <div class="mb-3">
                        <label class="fs-9" for="eventEndDate">End Date</label>
                        <input class="form-control datetimepicker" id="eventEndDate" type="text" name="endDate" placeholder="yyyy/mm/dd hh:mm" data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}' />
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="eventAllDay" name="allDay" />
                        <label class="form-check-label" for="eventAllDay">All Day
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="fs-9">Schedule Meeting</label>
                        <div><a class="btn badge-subtle-success btn-sm" href="#!"><span class="fas fa-video me-2"></span>Add video conference link</a></div>
                    </div>
                    <div class="mb-3">
                        <label class="fs-9" for="eventDescription">Description</label>
                        <textarea class="form-control" rows="3" name="description" id="eventDescription"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="fs-9" for="eventLabel">Label</label>
                        <select class="form-select" id="eventLabel" name="label">
                            <option value="" selected="selected">None</option>
                            <option value="primary">Business</option>
                            <option value="danger">Important</option>
                            <option value="success">Personal</option>
                            <option value="warning">Must Attend</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end align-items-center bg-body-tertiary border-0"><a class="me-3 text-600" href="../app/events/create-an-event.html">More options</a>
                    <button class="btn btn-primary px-4" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="Views/Resources/js/graf.js"></script>