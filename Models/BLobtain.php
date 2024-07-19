<?php
// Incluir tus archivos y configuraciones de base de datos aquí
require_once "Connection.php";

function ObtainData()
{
    $conn = Connection::connectionBD();

    if (isset($_GET['action']) && isset($_GET['id'])) {
        $action = $_GET['action'];
        $id = $_GET['id'];

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
            default:
                echo json_encode(array('error' => 'Acción no válida'));
                return;
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(array('error' => 'No se encontraron datos para el ID proporcionado'));
        }
    } else {
        echo json_encode(array('error' => 'Acción o ID de usuario no proporcionado'));
    }
}

ObtainData();
