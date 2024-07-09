<div id="tableExample3" data-list='{"valueNames":["name","email","app","apm","rol"],"page":10,"pagination":true}'>
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
                <a href="Empleados"><button class="btn btn-falcon-primary btn-sm me-2" type="button"><span class="fas fa-lock-open" data-fa-transform="shrink-3 down-2"></span><span class="ms-1">Activos</span></button></a>
            </div>
        </div>
        <div class="tab-pane fade show active" id="activos" role="tabpanel" aria-labelledby="activos-tab">
            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs-10 mb-0">
                    <thead class="bg-300">
                        <tr>
                            <th class="text-900 sort" data-sort="foto">Foto</th>
                            <th class="text-900 sort" data-sort="No">Nombre</th>
                            <th class="text-900 sort" data-sort="name">Correo</th>
                            <th class="text-900 sort" data-sort="app">Telefono</th>
                            <th class="text-900 sort" data-sort="apm">Departamento</th>
                            <th class="text-900 sort" data-sort="email">Rol</th>
                            <th class="text-900 sort" data-sort="email">No.Empleado</th>
                            <th></th>
                            <th class="text-900 sort">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php
                        try {
                            $usuarios = EmployeeController::getUsersInactive();
                            if (!empty($usuarios)) {
                                foreach ($usuarios as $usuario) {
                        ?>
                                    <tr>
                                        <td>
                                            <div class="avatar avatar-l me-2">
                                                <img class="rounded-circle" src="<?php echo htmlspecialchars('./Views/Resources/img/' . $usuario['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($usuario['nombreEmpleado']); ?>" />
                                            </div>
                                        </td>
                                        <td class="nombre">
                                            <a href="Perfil?noEmpleado=<?php echo htmlspecialchars($usuario['noEmpleado']); ?>">
                                                <?php echo htmlspecialchars($usuario['nombreEmpleado'] . ' ' . $usuario['apellidoPaterno'] . ' ' . $usuario['apellidoMaterno']); ?>
                                            </a>
                                        </td>
                                        <td class="email"><?php echo htmlspecialchars($usuario['emailEmpleado']); ?></td>
                                        <td class="tel"><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                        <td class="rold"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                        <td class="rol"><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                        <td class="No"><?php echo htmlspecialchars($usuario['noEmpleado']); ?></td>
                                        <td><a href="">Editar permisos</a></td>
                                        <td>
                                            <a class="btn btn-primary" href="../controller/users/unlock.php?id=<?php echo htmlspecialchars($usuario['idEmpleado']); ?>">
                                                <span class="fas fa-lock-open"></span>
                                            </a>
                                            <a class="btn btn-danger" href="../controller/users/delete.php?id=<?php echo htmlspecialchars($usuario['idEmpleado']); ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario? \n\n¡Esta acción eliminará completamente el usuario de la base de datos!');">
                                                <span class="far fa-trash-alt"></span>
                                            </a>
                                        </td>
                                    </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='10'>No se encontraron registros.</td></tr>";
                            }
                        } catch (Exception $e) {
                            // Manejo de errores: Muestra un mensaje amigable al usuario
                            echo '<tr><td colspan="10">Error al obtener empleados activos: ' . $e->getMessage() . '</td></tr>';
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