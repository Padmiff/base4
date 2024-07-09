<?php
require_once "Connection.php";

class BLclients
{
    static public function getClientActive()
    {
        try {
            $conn = Connection::connectionBD();  // Obtiene la conexión a la base de datos

            $sql = "SELECT * FROM client WHERE estadoCuenta = 'Activo'";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $cliente = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $cliente;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
        // $conn = null;
    }

    static public function getClientInactive()
    {
        try {
            $conn = Connection::connectionBD();  // Obtiene la conexión a la base de datos

            $sql = "SELECT * FROM client WHERE estadoCuenta = 'Inactivo'";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $cliente = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $cliente;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLgetClientAll()
    {
        try {
            $conn = Connection::connectionBD();
            $sql = "SELECT c.nombreCliente, c.apellidoPaterno, c.pais, c.estado, c.direccion, con.telefono, c.idCliente
                    FROM client c
                    LEFT JOIN contactocliente con ON c.idCliente = con.idCliente
                    WHERE con.idTipoContato = 1 OR con.idTipoContato IS NULL";

            $stmt = $conn->query($sql);
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $clientes;
        } catch (PDOException $e) {
            // Manejo de errores de PDO
            echo 'Error en la consulta: ' . $e->getMessage();
            return [];
        }
    }

    static public function BLgetClientbyId($idCliente)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO
            $sql = "SELECT c.nombre, c.apellidoPaterno, c.telefono, c.email, t.tipoContacto, c.horaAtencionSemana, c.horaAtencionFinseman
                    FROM contactocliente c
                    LEFT JOIN tipocontactocliente t ON c.idTipoContato = t.idTipoContacto
                    WHERE c.idCliente = :idCliente";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_STR);
            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultados;
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return [];
        }
    }
}
