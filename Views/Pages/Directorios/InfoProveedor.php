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