<?php
// Incluir el archivo de conexión
require 'conexion.php';

// Establecer encabezados de contenido
header('Content-Type: application/json');

try {
    // Consulta SQL para obtener los departamentos
    $query = "SELECT nombre FROM Departamento";
    $stmt = $conn->query($query);
    $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir resultados a JSON y devolverlos
    echo json_encode($departamentos);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la conexión: ' . $e->getMessage()]);
}
?>
