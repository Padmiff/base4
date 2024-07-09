<?php
// Establecer la conexión a la base de datos
require 'conexion.php';


// Establecer el encabezado para JSON
header('Content-Type: application/json');


try {

    // Obtener datos del formulario
    $N_empleado = $_POST['N_empleado'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $Nom_colab = $_POST['Nom_colab'] ?? '';
    $apellido_paterno = $_POST['apellido_paterno'] ?? '';
    $apellido_materno = $_POST['apellido_materno'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $aviso = $_POST['aviso'] ?? '';

    // Validar que los datos obligatorios estén presentes
    if (empty($N_empleado) || empty($Nom_colab) || empty($apellido_paterno) || empty($correo) || empty($numero) || empty($aviso)) {
        echo json_encode(['error' => 'Todos los campos obligatorios deben ser completados.']);
        exit;
    }

    // Preparar la consulta SQL para insertar el colaborador
    $query = "INSERT INTO colaborador (noColaborador, nombreColaborador, apellidoPaterno, apellidoMaterno, emailColaborador, telefono, tipo, estadoColaborador)
              VALUES (:noColaborador, :nombreColaborador, :apellidoPaterno, :apellidoMaterno, :emailColaborador, :telefono, :departamento, 'Empleado', 'Activo', :departamento))";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':noColaborador', $N_empleado);
    $stmt->bindParam(':nombreColaborador', $Nom_colab);
    $stmt->bindParam(':apellidoPaterno', $apellido_paterno);
    $stmt->bindParam(':apellidoMaterno', $apellido_materno);
    $stmt->bindParam(':emailColaborador', $correo);
    $stmt->bindParam(':telefono', $numero);
    $stmt->bindParam(':departamento', $departamento);
    $stmt->execute();

    echo json_encode(['success' => 'Colaborador insertado correctamente.']);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al insertar colaborador: ' . $e->getMessage()]);
}
?>
