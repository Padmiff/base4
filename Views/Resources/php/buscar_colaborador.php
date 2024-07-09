<?php
// Incluye archivos necesarios y establece la conexión si es necesario
require 'conexion.php';

// Función para obtener datos del empleado por número de colaborador
function getEmployeeData($numColaborador) {
    global $conn;

    // Preparar la consulta SQL con PDO
    $sql = "SELECT departamento, nombrecolaborador, apellidopaterno, apellidomaterno, emailcolaborador, numero_telefono 
            FROM colaboradores 
            WHERE noColaborador = :numColaborador";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bindValue(':numColaborador', $numColaborador, PDO::PARAM_INT);

    // Ejecutar consulta
    $stmt->execute();

    // Obtener resultado
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        // Devolver array con los datos
        return $result;
    } else {
        return false; // Devolver falso si no se encontraron resultados
    }
}

// Ejemplo de uso:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numColaborador = $_POST['N_empleado']; // Nombre del input en tu formulario

    // Llamar a la función para obtener los datos del empleado
    $employeeData = getEmployeeData($numColaborador);

    // Si se encontraron datos, mostrarlos o usarlos según necesites
    if ($employeeData) {
        // Devolver los datos como JSON (o utilizarlos de otra manera)
        echo json_encode($employeeData);
    } else {
        echo json_encode(['error' => 'No se encontraron datos para el número de colaborador proporcionado.']);
    }
}

// Cerrar conexión
$conn = null;
?>
