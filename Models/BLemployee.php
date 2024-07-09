<?php
require_once "Connection.php";

class BLemployee
{
    static public function getEmployeeActive()
    {
        try {
            $conn = Connection::connectionBD();  // Obtiene la conexión a la base de datos

            $sql = "SELECT idEmpleado, foto, noEmpleado, nombreEmpleado, apellidoPaterno, apellidoMaterno, emailEmpleado, telefono, r.nombre as rol, d.nombre
                    FROM empleado as e
                    INNER JOIN rol as r ON e.idRol = r.idRol
                    INNER JOIN departamento as d ON e.idDepartamento = d.idDepto
                    WHERE estadoEmpleado = 'Activo'";

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
            $conn = Connection::connectionBD();  // Obtiene la conexión a la base de datos

            $sql = "SELECT idEmpleado,foto ,noEmpleado, nombreEmpleado, apellidoPaterno, apellidoMaterno, emailEmpleado, telefono, r.nombre as rol, d.nombre
                    FROM empleado as e 
                    INNER JOIN rol as r on e.idRol=r.idRol 
                    INNER JOIN departamento as d on e.idDepartamento=d.idDepto 
                    WHERE estadoEmpleado = 'Inactivo'";

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
            $conn = Connection::connectionBD();

            $sql = "SELECT e.*, r.nombre as rol, d.nombre
                    FROM empleado e 
                    INNER JOIN Rol r ON e.idRol = r.idRol 
                    INNER JOIN Departamento d ON e.idDepartamento = d.idDepto 
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
            $conn = Connection::connectionBD();

            $sql = "SELECT e.*, r.nombre as rol, d.nombre
                    FROM empleado e 
                    INNER JOIN Rol r ON e.idRol = r.idRol 
                    INNER JOIN Departamento d ON e.idDepartamento = d.idDepto";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }
}
