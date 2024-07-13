<?php
require_once "Connection.php";

class BLproviders
{
    private static $conn;

    private static function getConnection()
    {
        if (!isset(self::$conn)) {
            self::$conn = Connection::connectionBD();
        }
        return self::$conn;
    }

    static public function getProvidersActive()
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT * FROM proveedores WHERE estadoProveedor = 'Activo'";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $proveedores;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function getProvidersInactive()
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT * FROM proveedores WHERE estadoProveedor = 'Inactivo'";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $proveedores;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLgetProvidersAll()
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT p.*,c.telefono, c.email
                    FROM proveedores as p
                    LEFT JOIN contactoproveedor as c ON p.idProveedor = c.idProveedor
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
            $conn = self::getConnection();

            $sql = "SELECT c.*, t.tipoContacto
                    FROM contactoproveedor as c
                    LEFT JOIN tipocontacto as t ON c.idTipoContacto = t.idTipoContacto
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

    static public function BLblockprovider($idProveedor)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE proveedores SET estadoProveedor = 'Inactivo' WHERE idProveedor = :idProveedor";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLunlockprovider($idProveedor)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE proveedores SET estadoProveedor = 'Activo' WHERE idProveedor = :idProveedor";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLdeleteprovider($idProveedor)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE proveedores SET estadoProveedor = 'Eliminado' WHERE idProveedor = :idProveedor";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }
}
