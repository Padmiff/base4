<?php
// Incluye la conexión a la base de datos
require 'Connection.php';

// Establecer el encabezado para JSON
header('Content-Type: application/json');

// Conectar a la base de datos
try {
    $conn = Connection::connectionBD();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Conexión a la base de datos fallida: ' . $e->getMessage()]);
    exit;
}

// Obtener el número de empleado desde la solicitud POST
if (isset($_POST['N_empleado'])) {
    $N_empleado = $_POST['N_empleado'];

    // Consulta para obtener los datos específicos del empleado
    $query = "SELECT nombreEmpleado, apellidoPaterno, apellidoMaterno, emailEmpleado, telefono, idDepartamento FROM empleado WHERE noEmpleado = :noEmpleado";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':noEmpleado', $N_empleado, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['success' => true, 'data' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Colaborador no encontrado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Campo N_empleado no definido en la solicitud POST.']);
}
