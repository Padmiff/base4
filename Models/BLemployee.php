<?php
require_once "Connection.php";

class BLemployee
{
    private static $conn;

    private static function getConnection()
    {
        if (!isset(self::$conn)) {
            self::$conn = Connection::connectionBD();
        }
        return self::$conn;
    }

    static public function getEmployeeActive()
    {
        try {
            $conn = self::getConnection();  // Obtiene la conexión a la base de datos

            $sql = "SELECT e.*, r.nombre as rol, d.nombre
                    FROM empleado as e
                    INNER JOIN rol as r ON e.idRol = r.idRol
                    INNER JOIN departamento as d ON e.idDepartamento = d.idDepto
                    WHERE estadoEmpleado = 'Activo';";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $empleados;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function getEmployeeInactive()
    {
        try {
            $conn = self::getConnection(); // Obtiene la conexión a la base de datos

            $sql = "SELECT e.*, r.nombre as rol, d.nombre
                    FROM empleado as e
                    INNER JOIN rol as r ON e.idRol = r.idRol
                    INNER JOIN departamento as d ON e.idDepartamento = d.idDepto
                    WHERE estadoEmpleado = 'Inactivo';";

            $stmt = $conn->prepare($sql);  // Prepara la consulta SQL
            $stmt->execute();  // Ejecuta la consulta

            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtiene todos los resultados

            return $empleados;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function getEmployeebyId($noEmpleado)
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT e.*, r.nombre as rol, d.nombre
                    FROM empleado as e 
                    INNER JOIN Rol as r ON e.idRol = r.idRol 
                    INNER JOIN departamento as d ON e.idDepartamento = d.idDepto 
                    WHERE e.noEmpleado = :noEmpleado";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':noEmpleado', $noEmpleado, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
            return null;
        }
    }

    static public function BLgetEmployeeAll()
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT e.*, r.nombre as rol, d.nombre
                    FROM empleado as e 
                    INNER JOIN Rol as r ON e.idRol = r.idRol 
                    INNER JOIN Departamento as d ON e.idDepartamento = d.idDepto";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLblockemployee($idEmpleado)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE empleado set estadoEmpleado = 'Inactivo' where idEmpleado = :idEmpleado;";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLunlockemployee($idEmpleado)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE empleado set estadoEmpleado = 'Activo' where idEmpleado = :idEmpleado;";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    static public function BLdeleteEmployee($idEmpleado)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE empleado set estadoEmpleado = 'Eliminado' where idEmpleado = :idEmpleado;";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }
}
