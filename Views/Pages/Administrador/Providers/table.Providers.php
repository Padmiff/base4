<div id="tableExample3" data-list='{"valueNames":["foto","No","name","app","apm","email","tel","rold","rol"],"page":10,"pagination":true}'>
    <div class="row justify-content-end g-0 mt-4">
        <ul class="nav nav-tabs mb-1" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="Empleados">
                    <button class="nav-link" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button" role="tab" aria-controls="activos" aria-selected="true">
                        Empleados</button>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="Proveedores">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bloqueados" type="button" role="tab" aria-controls="bloqueados" aria-selected="false">
                        Proveedores</button>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="Clientes">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bloqueados" type="button" role="tab" aria-controls="bloqueados" aria-selected="false">
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
                <a href="ProveedoresBloqueados"><button class="btn btn-falcon-danger btn-sm me-2" type="button"><span class="fas fa-lock" data-fa-transform="shrink-3 down-2"></span><span class="ms-1">Bloqueados</span></button></a>
                <div data-bs-target="#exampleModal" data-bs-toggle="modal"><button class="btn btn-falcon-success btn-sm" type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span class="ms-1">Agregar</span></button></div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="activos" role="tabpanel" aria-labelledby="activos-tab">
            <div class="table-responsive scrollbar">
                <table class="table table-bordered table-striped fs-10 mb-0">
                    <thead class="bg-300">
                        <tr>
                            <th class="text-900 sort" data-sort="name">Logo</th>
                            <th class="text-900 sort" data-sort="name">Proveedor</th>
                            <th class="text-900 sort" data-sort="name">Pais</th>
                            <th class="text-900 sort" data-sort="app">Estado</th>
                            <th class="text-900 sort" data-sort="apm">Ciudad</th>
                            <th class="text-900 sort" data-sort="email">Sitio Web</th>
                            <th class="text-900 sort" data-sort="email">Fecha de registro</th>
                            <th class="text-900 sort" data-sort="rol">Fecha de ultima Actividad</th>
                            <th></th>
                            <th class="text-900 sort">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php
                        try {
                            $usuarios = ProvidersController::getUsersActive();
                            if (!empty($usuarios)) {
                                foreach ($usuarios as $usuario) {
                        ?>
                                    <tr>
                                        <td class="name"><?php echo htmlspecialchars($usuario['logo']); ?></td>
                                        <td>
                                            <a href="Contacto?idProveedor=<?php echo htmlspecialchars($usuario['idProveedor']); ?>">
                                                <?php echo htmlspecialchars($usuario['nombreEmpresa']); ?>
                                            </a>
                                        </td>
                                        <td class="apm"><?php echo htmlspecialchars($usuario['pais']); ?></td>
                                        <td class="email"><?php echo htmlspecialchars($usuario['estado']); ?></td>
                                        <td class="rol"><?php echo htmlspecialchars($usuario['ciudad']); ?></td>
                                        <td class="rol"><?php echo htmlspecialchars($usuario['sitioWeb']); ?></td>
                                        <td class="rol"><?php echo htmlspecialchars($usuario['fechaRegistro']); ?></td>
                                        <td class="rol"><?php echo htmlspecialchars($usuario['fechaUltimaActividad']); ?></td>
                                        <td><a href="">Editar permisos</a></td>
                                        <td>
                                            <a class="btn btn-info btn-actualizar" data-bs-target="#update" data-bs-toggle="modal" data-id="<?php echo htmlspecialchars($usuario['idProveedor']); ?>">
                                                <span class="fas fa-sync-alt"></span>
                                            </a>
                                            <a class="btn btn-warning" href="../controller/users/lock.php?id=<?php echo htmlspecialchars($usuario['idProveedor']); ?>" onclick="return confirm('¿Estás seguro de bloquear este usuario?');">
                                                <span class="fas fa-lock"></span>
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