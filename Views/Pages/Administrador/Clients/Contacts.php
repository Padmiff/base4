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
// Obtener el idCliente de la URL
$idCliente = isset($_GET['idCliente']) ? $_GET['idCliente'] : '';

// Variable para mensaje de error
$mensaje = '';

if ($idCliente) {
    // Instanciar el controlador ClienteController
    $controller = new ClientsController();

    // Obtener la información del cliente por idCliente
    $cliente = $controller->getClientbyId($idCliente);

    // Verificar si hay resultados
    if (!$cliente) {
        // Mensaje de alerta si no hay contactos
        $mensaje = '<div class="alert alert-danger text-center" role="alert">
                      El cliente no tiene contactos registrados.
                    </div>';
    }
} else {
    $mensaje = '<div class="alert alert-danger" role="alert">
                      <h4 class="alert-heading fw-semi-bold">Contacto no encontrado</h4>
                      <p>No se encontró información para el cliente con el ID proporcionado.</p>
                      <hr />
                      <p class="mb-0">Verifica el ID del cliente e intenta de nuevo.</p>
                    </div>';
}

if (isset($_SESSION['alert_message']) && isset($_SESSION['alert_type'])) {
    $alertMessage = $_SESSION['alert_message'];
    $alertType = $_SESSION['alert_type'];
    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_type']);

    // Agregar el mensaje de alerta al mensaje general
    $mensaje .= '<div id="alertBlock" class="alert-fixed alert alert-' . $alertType . ' alert-dismissible fade show mt-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="bg-' . $alertType . ' me-3 icon-item"><span class="fas fa-check-circle text-white fs-6"></span></div>
                        <p class="mb-0 flex-1">' . $alertMessage . '</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>';
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['idContacto'])) {
    $idContacto = $_GET['idContacto'];

    // Llama al método para bloquear el empleado
    try {
        ContactsClientsController::deleteContact($idContacto, $idCliente);
        echo
        '<div id="alertBlock" class="alert-fixed alert alert-success border-0 d-flex align-items-center" role="alert">
            <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-6"></span></div>
            <p class="mb-0 flex-1">El contacto ha sido Eliminado correctamente.</p><button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } catch (Exception $e) {
        echo
        '<div id="alertBlock" class="alert-fixed alert alert-danger border-0 d-flex align-items-center alert-fixed" role="alert">
            <div class="bg-danger me-3 icon-item"><span class="fas fa-times-circle text-white fs-6"></span></div>
            <p class="mb-0 flex-1">Error al Eliminar al contacto: ' . $e->getMessage() . '</p><button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}
?>
<div class="card-title text-white mt-4">
    <h3 class="mt-3">Información Clientes</h3>
</div>

<div id="tableExample3" data-list='{"valueNames":["nombre","email","tel","tipoContacto","semanada","finsemana","notas"],"page":10,"pagination":true}'>
    <div class="tab-content mt-3">
        <div class="d-flex justify-content-end align-items-center my-3">
            <a href="Clientes" class="btn btn-falcon-primary btn-sm ms-2" type="button">
                <span class="fas fa-arrow-left" data-fa-transform="shrink-3 down-2"></span>
                <span class="ms-1">Clientes</span>
            </a>
            <div data-bs-target="#agregar" data-idd="<?php echo htmlspecialchars($idCliente); ?>" data-bs-toggle="modal">
                <button class="btn btn-falcon-success btn-sm btn-id ms-2" type="button">
                    <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                    <span class="ms-1">Agregar</span>
                </button>
            </div>
        </div>
        <div class="table-responsive scrollbar">
            <table class="table table-bordered table-striped fs-10 mb-0">
                <thead class="bg-300">
                    <tr>
                        <th class="text-900 sort" data-sort="nombre">Nombre</th>
                        <th class="text-900 sort" data-sort="email">Email</th>
                        <th class="text-900 sort" data-sort="tel">Teléfono</th>
                        <th class="text-900 sort" data-sort="tipoContacto">Tipo de Contacto</th>
                        <th class="text-900 sort" data-sort="semanada">HS ATT Semana</th>
                        <th class="text-900 sort" data-sort="finsemana">HS ATT Finsemana</th>
                        <th class="text-900 sort">Acciones</th>
                    </tr>
                </thead>
                <tbody class="list">
                    <?php if ($cliente) : ?>
                        <?php foreach ($cliente as $row) : ?>
                            <tr>
                                <td class="align-middle white-space-nowrap nombre">
                                    <?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno']); ?>
                                </td>
                                <td class="align-middle white-space-nowrap email"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="tel"><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td class="tipoContacto"><?php echo htmlspecialchars($row['tipoContacto']); ?></td>
                                <td class="semanada"><?php echo htmlspecialchars($row['horaAtencionSemana']); ?></td>
                                <td class="finsemana"><?php echo htmlspecialchars($row['horaAtencionFinseman']); ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-info me-2 btn-actualizar" data-bs-target="#update" data-bs-toggle="modal" data-id="<?php echo htmlspecialchars($row['idContacto']); ?>">
                                            <span class="fas fa-sync-alt"></span>
                                        </a>
                                        <a class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#EliminarModal<?php echo htmlspecialchars($row['idContacto']); ?>">
                                            <span class="far fa-trash-alt"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if ($mensaje) : ?>
                <?php echo $mensaje; ?>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev">
                <span class="fas fa-chevron-left"></span>
            </button>
            <ul class="pagination mb-0"></ul>
            <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next">
                <span class="fas fa-chevron-right"></span>
            </button>
        </div>
    </div>
</div>

<!-- Moodal para Insertar Contacto -->
<div class="modal fade" id="agregar" tabindex="-1" role="dialog" aria-labelledby="agregarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="row text-start justify-content-between align-items-center mb-2">
                    <div class="col-auto">
                        <h5 id="agregarModalLabel">Nuevo Contacto</h5>
                    </div>
                    <button class="btn-close mb-1" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php ContactsClientsController::postInsertContactos() ?>
                <form action="" method="post">
                    <input type="hidden" name="idCliente" id="idCliente_update">
                    <input type="hidden" name="estadoContacto" value="Activo">
                    <div class="mb-3">
                        <input class="form-control" type="text" name="nombre" placeholder="Nombre" required />
                    </div>
                    <div class="row gx-1 mb-2">
                        <div class="col-sm-6 mb-1">
                            <input class="form-control" type="text" name="apellidoPaterno" placeholder="Apellido Paterno" required />
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="apellidoMaterno" placeholder="Apellido Materno" required />
                        </div>
                    </div>
                    <div class="mb-2">
                        <input class="form-control" type="email" name="email" placeholder="Correo" required />
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="notas" placeholder="Notas"></textarea>
                    </div>
                    <div class="row gx-1">
                        <hr>
                        <div class="col-sm-6 mb-1">
                            <button class="btn btn-primary d-block mx-1 w-100" type="submit" name="registrar">Agregar</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-secondary d-block mx-1 w-100" type="reset">Restaurar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener todos los botones de agregar contacto
        // var addContactButtons = document.querySelectorAll('[data-bs-target="#agregar"]');
        var addContactButtons = document.querySelectorAll('[data-bs-target="#agregar"]');

        // Iterar sobre cada botón de agregar contacto
        addContactButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

                var idCliente = this.getAttribute('data-idd');

                // Asignar el valor de idCliente al campo oculto dentro del modal
                var idClienteInput = document.getElementById('idCliente_update');
                if (idClienteInput) {
                    idClienteInput.value = idCliente;
                }
            });
        });
    });
</script>

<!-- Modal para Actualizar Contacto -->
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="row text-start justify-content-between align-items-center mb-2">
                    <div class="col-auto">
                        <h5 id="updateModalLabel">Actualizar Contacto</h5>
                    </div>
                    <button class="btn-close mb-1" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php ContactsClientsController::postUpdateContact() ?>
                <form action="" method="post">
                    <input type="hidden" name="idCliente" id="idClients">
                    <input type="hidden" name="idContacto" id="idContacto_update">
                    <div class="mb-3">
                        <input class="form-control" type="text" name="nombre" id="nombre_update" placeholder="Nombre" required />
                    </div>
                    <div class="row gx-1 mb-2">
                        <div class="col-sm-6 mb-1">
                            <input class="form-control" type="text" name="apellidoPaterno" id="apellidoPaterno_update" placeholder="Apellido Paterno" required />
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="apellidoMaterno" id="apellidoMaterno_update" placeholder="Apellido Materno" required />
                        </div>
                    </div>
                    <div class="mb-2">
                        <input class="form-control" type="email" name="email" id="email_update" placeholder="Correo" required />
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="notas" id="notas_update" placeholder="Notas"></textarea>
                    </div>
                    <div class="row gx-1">
                        <hr>
                        <div class="col-sm-6 mb-1">
                            <button class="btn btn-primary d-block mx-1 w-100" type="submit" name="actualizar">Actualizar</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-secondary d-block mx-1 w-100" type="reset">Restaurar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var updateButtons = document.querySelectorAll('.btn-actualizar');

        updateButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                var userId = this.getAttribute('data-id');

                fetch('Models/BLobtain.php?action=ObtainContactClient&id=' + userId)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('idClients').value = data.idCliente;
                        document.getElementById('idContacto_update').value = data.idContacto;
                        document.getElementById('nombre_update').value = data.nombre;
                        document.getElementById('apellidoPaterno_update').value = data.apellidoPaterno;
                        document.getElementById('apellidoMaterno_update').value = data.apellidoMaterno;
                        document.getElementById('email_update').value = data.email;
                        document.getElementById('notas_update').value = data.notas;
                    })
                    .catch(error => console.error('Error al obtener datos del usuario', error));
            });
        });
    });
</script>
<?php foreach ($cliente as $row) : ?>
    <div class="modal fade" id="EliminarModal<?php echo htmlspecialchars($row['idContacto']); ?>" tabindex="-1" aria-labelledby="EliminarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EliminarModalLabel">Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de eliminar a <?php echo htmlspecialchars($row['nombre']); ?>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="?action=delete&idContacto=<?php echo htmlspecialchars($row['idContacto']); ?>&idCliente=<?php echo htmlspecialchars($idCliente); ?>">
                        <button type="button" class="btn btn-danger">Eliminar</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script src="Views/Resources/assets/js/alerts.js"></script>