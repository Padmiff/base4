<?php
// Establecer la conexión a la base de datos
require 'Connection.php';

// Establecer el encabezado para JSON
header('Content-Type: application/json');

try {
    $conn = Connection::connectionBD();
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
    exit;
}

try {
    // Obtener datos del formulario
    $N_empleado = $_POST['N_empleado'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $Nom_colab = $_POST['Nom_colab'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $numero = $_POST['numero'] ?? '';

    // Validar que los datos obligatorios estén presentes
    if (empty($N_empleado) || empty($Nom_colab) || empty($apellido_paterno) || empty($correo) || empty($numero)) {
        echo json_encode(['error' => 'Todos los campos obligatorios deben ser completados.']);
        exit;
    }

    // Preparar la consulta SQL para insertar el colaborador
    $query = "INSERT INTO empleado (noEmpleado, nombreEmpleado, apellidoPaterno, apellidoMaterno, emailEmpleado, telefono, idDepartamento, tipo, estadoEmpleado)
              VALUES (:noEmpleado, :nombreEmpleado, :apellidoPaterno, :apellidoMaterno, :emailEmpleado, :telefono, :idDepartamento, 'Empleado', 'Activo')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':noEmpleado', $N_empleado);
    $stmt->bindParam(':nombreEmpleado', $Nom_colab);
    $stmt->bindParam(':apellidoPaterno', $apellido_paterno);
    $stmt->bindParam(':apellidoMaterno', $apellido_materno);
    $stmt->bindParam(':emailEmpleado', $correo);
    $stmt->bindParam(':telefono', $numero);
    $stmt->bindParam(':idDepartamento', $departamento);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => 'Colaborador insertado correctamente.']);
    } else {
        echo json_encode(['error' => 'No se pudo insertar el colaborador.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al insertar colaborador: ' . $e->getMessage()]);
}
