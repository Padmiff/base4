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
?>

<div class="card-title text-white mt-4">
    <h3 class="mt-3">Información Clientes</h3>
</div>

<div id="tableExample3" data-list='{"valueNames":["nombre","email","tel","tipoContacto","semanada","finsemana","notas"],"page":10,"pagination":true}'>
    <div class="tab-content mt-3">
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