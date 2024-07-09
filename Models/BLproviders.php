<?php
require_once "Connection.php";

class BLproviders
{
    static public function getProvidersActive()
    {
        try {
            $conn = Connection::connectionBD();  // Obtiene la conexión a la base de datos

            $sql = "SELECT * FROM proveedores WHERE estadoProveedor = 'Activo'";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $empleados;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function getProvidersInactive()
    {
        try {
            $conn = Connection::connectionBD();  // Obtiene la conexión a la base de datos

            $sql = "SELECT * FROM proveedores WHERE estadoProveedor = 'inactivo'";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $empleados;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLgetProvidersAll()
    {
        try {
            $conn = Connection::connectionBD();

            $sql = "SELECT *,c.telefono, c.email
                    FROM proveedores p
                    LEFT JOIN contactoproveedor c ON p.idProveedor = c.idProveedor
                    WHERE c.idTipoContacto = 1 OR c.idTipoContacto IS NULL";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $proveedores;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLgetProvidersbyId($idProveedor)
    {
        try {
            $conn = Connection::connectionBD();
            $sql = "SELECT c.nombreProveedor, c.apellidoPaterno, c.telefono, c.email, t.tipoContacto, c.horaAtencionSemana, c.horaAtencionFinsemana
                    FROM contactoproveedor c
                    LEFT JOIN tipocontacto t ON c.idTipoContacto = t.idTipoContacto
                    WHERE c.idProveedor = :idProveedor";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idProveedor', $idProveedor, PDO::PARAM_STR);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultados;
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return [];
        }
    }
}
