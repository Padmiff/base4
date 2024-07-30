<?php
// Incluir archivos y configuraciones de base de datos

/**
 * Obtener datos de diferentes tablas basados en una acción y un ID proporcionado en la consulta GET.
 * 
 * La función `ObtainData` realiza una consulta a la base de datos para obtener información de una tabla específica
 * según la acción y el ID proporcionados en los parámetros de la solicitud GET.
 * 
 * Las acciones soportadas son:
 * - 'ObtainColab': Obtiene datos de la tabla `empleado`.
 * - 'ObtainClients': Obtiene datos de la tabla `client`.
 * - 'ObtainProvee': Obtiene datos de la tabla `proveedores`.
 * - 'ObtainContactClient': Obtiene datos de la tabla `contactocliente`.
 * - 'ObtainContactProvider': Obtiene datos de la tabla `contactoproveedor`.
 */
require_once "Connection.php";

function ObtainData()
{
    $conn = Connection::connectionBD();

    // Verifica si los parámetros 'action' y 'id' están presentes en la solicitud GET
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $action = $_GET['action']; // Acción solicitada
        $id = $_GET['id']; // ID del registro a obtener

        // Determina la consulta SQL según la acción proporcionada
        switch ($action) {
            case 'ObtainColab':
                $sql = "SELECT * FROM empleado WHERE idEmpleado = :id";
                break;
            case 'ObtainClients':
                $sql = "SELECT * FROM client WHERE idCliente = :id";
                break;
            case 'ObtainProvee':
                $sql = "SELECT * FROM proveedores WHERE idProveedor = :id";
                break;
            case 'ObtainContactClient':
                $sql = "SELECT * FROM contactocliente WHERE idContacto = :id";
                break;
            case 'ObtainContactProvider':
                $sql = "SELECT * FROM contactoproveedor WHERE idContacto = :id";
                break;
            default:
                echo json_encode(array('error' => 'Acción no válida'));
                return;
        }

        // Prepara y ejecuta la consulta SQL
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene los resultados de la consulta

        // Devuelve los datos en formato JSON o un mensaje de error si no se encontraron datos
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(array('error' => 'No se encontraron datos para el ID proporcionado'));
        }
    } else {
        // Parámetros 'action' o 'id' no proporcionados
        echo json_encode(array('error' => 'Acción o ID de usuario no proporcionado'));
    }
}
// Llama a la función para procesar la solicitud
ObtainData();
