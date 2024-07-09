<?php
// Incluir el archivo de conexión
require 'conexion.php';

// Establecer encabezados de contenido
header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el número de empleado enviado desde JavaScript
    $numeroEmpleado = $_GET['numero'] ?? '';

    // Consulta SQL para obtener los datos del empleado por número de colaborador
    $query = "SELECT idColaborador, nombreColaborador, apellidoPaterno, apellidoMaterno, emailColaborador, telefono, idDepartamento FROM colaborador WHERE noColaborador = :numeroEmpleado";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':numeroEmpleado', $numeroEmpleado);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Preparar respuesta JSON con datos del empleado
        $response = [
            'success' => true,
            'idColaborador' => $result['idColaborador'],
            'nombre' => $result['nombreColaborador'],
            'apellido_paterno' => $result['apellidoPaterno'],
            'apellido_materno' => $result['apellidoMaterno'],
            'correo' => $result['emailColaborador'],
            'telefono' => $result['telefono'],
            'departamento' => $result['idDepartamento']
            // Agrega más campos según sea necesario
        ];
    } else {
        // Si no se encuentra ningún empleado con ese número
        $response = [
            'success' => false
        ];
    }

    // Enviar respuesta JSON al cliente
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
