<style>
    .alert-fixed {
        position: fixed;
        top: 60px;
        right: 20px;
        z-index: 9999;
        width: 400px;
        opacity: 1;
        transition: opacity 0.5s ease;
    }
</style>
<?php
// Verifica si se ha solicitado bloquear un empleado
if (isset($_GET['action']) && $_GET['action'] === 'block' && isset($_GET['idEmpleado'])) {
    $idEmpleado = $_GET['idEmpleado'];

    // Llama al método para bloquear el empleado
    try {
        EmployeeController::blockEmployee($idEmpleado);
        echo
        '<div id="alertBlock" class="alert-fixed alert alert-success border-0 d-flex align-items-center" role="alert">
            <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-6"></span></div>
            <p class="mb-0 flex-1">El usuario ha sido bloqueado correctamente.</p><button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } catch (Exception $e) {
        echo
        '<div id="alertBlock" class="alert-fixed alert alert-danger border-0 d-flex align-items-center alert-fixed" role="alert">
            <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-6"></span></div>
            <p class="mb-0 flex-1">Error al bloquear al usuario: ' . $e->getMessage() . '</p><button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}
?>
<div id="tableExample3" data-list='{"valueNames":["foto","name","email","tel","dep","rol","No"],"page":10,"pagination":true}'>
    <div class="row justify-content-end g-0 mt-4">
        <ul class="nav nav-tabs mb-1" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" type="button" role="tab" aria-selected="true">
                    Colaboradores</button>
            </li>
            <li class="nav-item" role="presentation">
                <a href="Proveedores">
                    <button class="nav-link" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">
                        Proveedores</button>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="Clientes">
                    <button class="nav-link" data-bs-toggle="tab" type="button" role="tab" aria-selected="false">
                        Clientes</button>
                </a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="row justify-content g-0">
            <div class="col-auto col-sm-5">
                <form class="mt-4">
                    <div class="input-group"><input class="form-control form-control-sm shadow-none search" type="search" placeholder="Search..." aria-label="search" />
                        <div class="input-group-text bg-transparent"><span class="fa fa-search fs-10 text-600"></span></div>
                    </div>
                </form>
            </div>
            <div class="d-flex align-items-center justify-content-end my-3">
                <a href="EmpleadosBloqueados"><button class="btn btn-falcon-danger btn-sm me-2" type="button"><span class="fas fa-lock" data-fa-transform="shrink-3 down-2"></span><span class="ms-1">Bloqueados</span></button></a>
                <div data-bs-target="#exampleModal" data-bs-toggle="modal"><button class="btn btn-falcon-success btn-sm" type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span class="ms-1">Agregar</span></button></div>
            </div>
        </div>
        <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="activos-tab">
            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs-10 mb-0">
                    <thead class="bg-300">
                        <tr>
                            <th class="text-900 sort" data-sort="foto">Foto</th>
                            <th class="text-900 sort" data-sort="name">Nombre</th>
                            <th class="text-900 sort" data-sort="email">Correo</th>
                            <th class="text-900 sort" data-sort="tel">Telefono</th>
                            <th class="text-900 sort" data-sort="dep">Departamento</th>
                            <th class="text-900 sort" data-sort="rol">Rol</th>
                            <th class="text-900 sort" data-sort="No">No.Empleado</th>
                            <th></th>
                            <th class="text-900 sort">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php
                        try {
                            $usuarios = EmployeeController::getUsersActive();
                            if (!empty($usuarios)) {
                                foreach ($usuarios as $usuario) {
                        ?>
                                    <tr>
                                        <td>
                                            <div class="avatar avatar-l me-2 foto">
                                                <img class="rounded-circle" src="<?php echo htmlspecialchars('./Views/Resources/img/' . $usuario['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($usuario['nombreEmpleado']); ?>" />
                                            </div>
                                        </td>
                                        <td class="name">
                                            <a href="Perfil?noEmpleado=<?php echo htmlspecialchars($usuario['noEmpleado']); ?>">
                                                <?php echo htmlspecialchars($usuario['nombreEmpleado'] . ' ' . $usuario['apellidoPaterno'] . ' ' . $usuario['apellidoMaterno']); ?>
                                            </a>
                                        </td>
                                        <td class="email"><?php echo htmlspecialchars($usuario['emailEmpleado']); ?></td>
                                        <td class="tel"><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                        <td class="dep"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                        <td class="rol"><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                        <td class="No"><?php echo htmlspecialchars($usuario['noEmpleado']); ?></td>
                                        <td><a href="#">Editar permisos</a></td>
                                        <td>
                                            <a class="btn btn-info btn-actualizar" data-bs-target="#update" data-bs-toggle="modal" data-id="<?php echo htmlspecialchars($usuario['idEmpleado']); ?>">
                                                <span class="fas fa-sync-alt"></span>
                                            </a>
                                            <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#confirmacionModal<?php echo htmlspecialchars($usuario['idEmpleado']); ?>">
                                                <span class="fas fa-lock"></span>
                                            </a>
                                        </td>
                                    </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='9'>No se encontraron registros.</td></tr>";
                            }
                        } catch (Exception $e) {
                            // Manejo de errores: Muestra un mensaje amigable al usuario
                            echo '<tr><td colspan="9">Error al obtener empleados activos: ' . $e->getMessage() . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3"><button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                <ul class="pagination mb-0"></ul><button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para bloquear usuario -->
<?php foreach ($usuarios as $usuario) : ?>
    <div class="modal fade" id="confirmacionModal<?php echo htmlspecialchars($usuario['idEmpleado']); ?>" tabindex="-1" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmacionModalLabel">Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de bloquear a <?php echo htmlspecialchars($usuario['nombreEmpleado']); ?>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="?action=block&idEmpleado=<?php echo htmlspecialchars($usuario['idEmpleado']); ?>">
                        <button type="button" class="btn btn-warning">Bloquear</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<script src="Views/Resources/assets/js/alerts.js"></script>