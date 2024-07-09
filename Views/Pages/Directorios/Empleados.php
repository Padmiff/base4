<div class="card-title text-white mt-4">
    <h3 class="mb-0">Directorio de Empleados</h3>
</div>
<div id="tableExample3" data-list='{"valueNames":["foto","No","name","app","apm","email","tel","rold","rol"],"page":10,"pagination":true}'>
    <div class="tab-content mt-4">
        <div class="row justify-content g-0 mt-2">
            <div class="col-auto col-sm-5">
                <form class="mb-4">
                    <div class="input-group"><input class="form-control form-control-sm shadow-none search" type="search" placeholder="Search..." aria-label="search" />
                        <div class="input-group-text bg-transparent"><span class="fa fa-search fs-10 text-600"></span></div>
                    </div>
                </form>
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
                        </tr>
                    </thead>
                    <tbody class="list">
                        <?php
                        try {
                            $usuarios = EmployeeController::getEmployeeAll();
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