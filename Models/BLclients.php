<?php
require_once "Connection.php";

class BLclients
{
    private static $conn;

    private static function getConnection()
    {
        if (!isset(self::$conn)) {
            self::$conn = Connection::connectionBD();
        }
        return self::$conn;
    }

    static public function getClientActive()
    {
        try {
            $conn = self::getConnection();

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
            $conn = self::getConnection();

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
            $conn = self::getConnection();

            $sql = "SELECT c.*, con.telefono
                    FROM client as c
                    LEFT JOIN contactocliente as con ON c.idCliente = con.idCliente
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
            $conn = self::getConnection();

            $sql = "SELECT c.*, t.tipoContacto
                    FROM contactocliente as c
                    LEFT JOIN tipocontactocliente as t ON c.idTipoContato = t.idTipoContacto
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

    static public function BLblockclient($idCliente)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE client SET estadoCuenta = 'Inactivo' WHERE idCliente = :idCliente";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLunlockclient($idCliente)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE client SET estadoCuenta = 'Activo' WHERE idCliente = :idCliente";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLdeleteclient($idCliente)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE client SET estadoCuenta = 'Eliminado' WHERE idCliente = :idCliente";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }
}
