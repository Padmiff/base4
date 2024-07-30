<?php
require_once "Connection.php";

class DatosModel
{
    /**
     * Obtiene datos generales para gráficos, como el conteo de empleados, clientes y proveedores.
     * 
     * Array asociativo con el número de empleados, clientes y proveedores.
     * Si ocurre un error durante la consulta.
     */
    static public function obtenerDatosGrafica()
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión a la base de datos

            // Consulta para contar empleados
            $sqlEmpleados = "SELECT COUNT(*) as total FROM empleado";
            $stmtEmpleados = $conn->prepare($sqlEmpleados);
            $stmtEmpleados->execute();
            $numEmpleados = $stmtEmpleados->fetch(PDO::FETCH_ASSOC)['total'];

            // Consulta para contar clientes
            $sqlClientes = "SELECT COUNT(*) as total FROM client";
            $stmtClientes = $conn->prepare($sqlClientes);
            $stmtClientes->execute();
            $numClientes = $stmtClientes->fetch(PDO::FETCH_ASSOC)['total'];

            // Consulta para contar proveedores
            $sqlProveedores = "SELECT COUNT(*) as total FROM proveedores";
            $stmtProveedores = $conn->prepare($sqlProveedores);
            $stmtProveedores->execute();
            $numProveedores = $stmtProveedores->fetch(PDO::FETCH_ASSOC)['total'];

            $conn = null;

            return [
                'empleados' => $numEmpleados,
                'clientes' => $numClientes,
                'proveedores' => $numProveedores
            ];
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene datos de conteo de elementos inactivos, como empleados, clientes y proveedores inactivos.
     * 
     * Array asociativo con el número de empleados, clientes y proveedores inactivos.
     * Si ocurre un error durante la consulta.
     */
    static public function obtenerDatosGraficaInactivos()
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión a la base de datos

            // Consulta para contar empleados inactivos
            $sqlEmpleadosInactivos = "SELECT COUNT(*) as total FROM empleado WHERE estadoEmpleado = 'inactivo'";
            $stmtEmpleadosInactivos = $conn->prepare($sqlEmpleadosInactivos);
            $stmtEmpleadosInactivos->execute();
            $numEmpleadosInactivos = $stmtEmpleadosInactivos->fetch(PDO::FETCH_ASSOC)['total'];

            // Consulta para contar clientes inactivos
            $sqlClientesInactivos = "SELECT COUNT(*) as total FROM client WHERE estadoCuenta = 'inactivo'";
            $stmtClientesInactivos = $conn->prepare($sqlClientesInactivos);
            $stmtClientesInactivos->execute();
            $numClientesInactivos = $stmtClientesInactivos->fetch(PDO::FETCH_ASSOC)['total'];

            // Consulta para contar proveedores inactivos
            $sqlProveedoresInactivos = "SELECT COUNT(*) as total FROM proveedores WHERE estadoProveedor = 'inactivo'";
            $stmtProveedoresInactivos = $conn->prepare($sqlProveedoresInactivos);
            $stmtProveedoresInactivos->execute();
            $numProveedoresInactivos = $stmtProveedoresInactivos->fetch(PDO::FETCH_ASSOC)['total'];

            $conn = null;

            return [
                'empleados' => $numEmpleadosInactivos,
                'clientes' => $numClientesInactivos,
                'proveedores' => $numProveedoresInactivos
            ];
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene el conteo de empleados, proveedores y clientes por género.
     * 
     * Array asociativo con el total de individuos masculinos y femeninos en la intranet.
     * Si ocurre un error durante la consulta.
     */
    static public function obtenerDatosGeneroIntranet()
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión a la base de datos

            // Consulta para contar individuos de género masculino y femenino
            $sqlGenero = "
                SELECT 
                    (SELECT COUNT(*) FROM empleado WHERE genero = 'Masculino') +
                    (SELECT COUNT(*) FROM contactoproveedor WHERE genero = 'Masculino') +
                    (SELECT COUNT(*) FROM contactocliente WHERE genero = 'Masculino') AS TotalMasculinoIntranet,
                    
                    (SELECT COUNT(*) FROM empleado WHERE genero = 'Femenino') +
                    (SELECT COUNT(*) FROM contactoproveedor WHERE genero = 'Femenino') +
                    (SELECT COUNT(*) FROM contactocliente WHERE genero = 'Femenino') AS TotalFemeninoIntranet;
            ";
            $stmtGenero = $conn->prepare($sqlGenero);
            $stmtGenero->execute();
            $result = $stmtGenero->fetch(PDO::FETCH_ASSOC);

            $conn = null;

            return [
                'TotalMasculinoIntranet' => $result['TotalMasculinoIntranet'],
                'TotalFemeninoIntranet' => $result['TotalFemeninoIntranet']
            ];
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene el conteo de incidencias por tipo: Internet, Hardware y Software.
     * 
     * Array asociativo con el número de incidencias por cada tipo.
     * Si ocurre un error durante la consulta.
     */
    public static function obtenerDatosPorTipoIncidencia()
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión a la base de datos

            // Consulta para contar incidencias de tipo 'Internet'
            $sqlInternet = "SELECT COUNT(*) as cantidad FROM reinci r WHERE r.tipo_inc = 'Internet';";
            $stmtInternet = $conn->prepare($sqlInternet);
            $stmtInternet->execute();
            $numInternet = $stmtInternet->fetch(PDO::FETCH_ASSOC)['cantidad'];

            // Consulta para contar incidencias de tipo 'Hardware'
            $sqlHardware = "SELECT COUNT(*) as cantidad FROM reinci r WHERE r.tipo_inc = 'Hardware';";
            $stmtHardware = $conn->prepare($sqlHardware);
            $stmtHardware->execute();
            $numHardware = $stmtHardware->fetch(PDO::FETCH_ASSOC)['cantidad'];

            // Consulta para contar incidencias de tipo 'Software'
            $sqlSoftware = "SELECT COUNT(*) as cantidad FROM reinci r WHERE r.tipo_inc = 'Software';";
            $stmtSoftware = $conn->prepare($sqlSoftware);
            $stmtSoftware->execute();
            $numSoftware = $stmtSoftware->fetch(PDO::FETCH_ASSOC)['cantidad'];

            $conn = null;

            return [
                'internet' => $numInternet,
                'hardware' => $numHardware,
                'software' => $numSoftware
            ];
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta a la base de datos: ' . $e->getMessage());
        }
    }
}
