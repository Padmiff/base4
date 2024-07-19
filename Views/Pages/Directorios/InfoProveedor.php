<?php
require_once 'Controllers/ProvidersController.php';

// Obtener el idProveedor de la URL
$idProveedor = isset($_GET['idProveedor']) ? $_GET['idProveedor'] : '';

// Variable para mensaje de error
$mensaje = '';

if ($idProveedor) {
    // Instanciar el controlador ProveedorController
    $controller = new ProvidersController();

    // Obtener la información del proveedor por idProveedor
    $proveedor = $controller->getProvidersbyId($idProveedor);

    // Verificar si hay resultados
    if (!$proveedor) {
        // Mensaje de alerta si no hay proveedor encontrado
        $mensaje = '<div class="alert alert-danger text-center" role="alert">
                      El proveedor no tiene contactos registrados.
                    </div>';
    }
} else {
    // Redirigir o manejar el error si no se proporciona idProveedor
    // Ejemplo: header('Location: error.php');
    // exit();
    $mensaje = '<div class="alert alert-danger" role="alert">
                  <h4 class="alert-heading fw-semi-bold">Contacto no encontrado</h4>
                  <p>No se encontró información para el proveedore con el ID proporcionado.</p>
                  <hr />
                  <p class="mb-0">Verifica el ID del proveedor e intenta de nuevo.</p>
                </div>';
}

?>

<div class="card-title text-white mt-4">
    <h3 class="mt-3">Información Proveedor</h3>
</div>
<div id="tableExample3" data-list='{"valueNames":["nombre","email","tel","tipoContacto","semana","finsemana","notas"],"page":10,"pagination":true}'>
    <div class="tab-content mt-3">
        <div class="d-flex align-items-center justify-content-end my-3">
            <div data-bs-target="#agregar" data-id="<?php echo htmlspecialchars($idProveedor); ?>" data-bs-toggle="modal">
                <button class="btn btn-falcon-success btn-sm btn-id" type="button">
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
                        <th class="text-900 sort" data-sort="semana">HS ATT Semana</th>
                        <th class="text-900 sort" data-sort="finsemana">HS ATT Finsemana</th>
                        <th class="text-900 sort" data-sort="notas">Notas</th>
                    </tr>
                </thead>
                <tbody class="list">
                    <?php foreach ($proveedor as $row) : ?>
                        <tr>
                            <td class="align-middle white-space-nowrap nombre">
                                <?php echo htmlspecialchars($row['nombreProveedor'] . ' ' . $row['apellidoPaterno'] . ' ' . $row['apellidoMaterno']); ?>
                            </td>
                            <td class="align-middle white-space-nowrap email"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="tel"><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td class="tipoContacto"><?php echo htmlspecialchars($row['tipoContacto']); ?></td>
                            <td class="semana"><?php echo htmlspecialchars($row['horaAtencionSemana']); ?></td>
                            <td class="finsemana"><?php echo htmlspecialchars($row['horaAtencionFinsemana']); ?></td>
                            <td class="notas"><?php echo htmlspecialchars($row['notas']); ?></td>
                        </tr>
                    <?php endforeach; ?>
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

<!--  Modal para insertar un contacto nuevo-->
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
                <?php ProvidersController::InsertContactos() ?>
                <form action="" id="formAgregarUsuario" method="post">
                    <input type="hidden" name="idProveedor" id="idProveedor_update">
                    <div class="mb-3">
                        <input class="form-control" type="text" name="nombreProveedor" placeholder="Nombre" required />
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
        var addContactButtons = document.querySelectorAll('[data-bs-target="#agregar"]');

        // Iterar sobre cada botón de agregar contacto
        addContactButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                var idProveedor = this.getAttribute('data-id');

                var idProveedorInput = document.getElementById('idProveedor_update');
                if (idProveedorInput) {
                    idProveedorInput.value = idProveedor;
                }
            });
        });
    });
</script>