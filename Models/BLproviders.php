<?php
require_once "Connection.php";

class BLproviders
{
    // Variable para almacenar la conexión a la base de datos
    private static $conn;

    /**
     * Obtiene la conexión a la base de datos.
     * 
     * Si la conexión aún no está establecida, se crea una nueva. De lo contrario, se devuelve la conexión existente.
     * 
     * La conexión a la base de datos.
     */
    private static function getConnection()
    {
        if (!isset(self::$conn)) {
            self::$conn = Connection::connectionBD();
        }
        return self::$conn;
    }

    /**
     * Obtiene todos los proveedores activos.
     * 
     * Ejecuta una consulta SQL para obtener proveedores cuyo estado es 'Activo'.
     * 
     * Un array de proveedores activos en formato asociativo.
     * Si ocurre un error en la consulta SQL.
     */
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

    /**
     * Obtiene todos los proveedores inactivos.
     * 
     * Ejecuta una consulta SQL para obtener proveedores cuyo estado es 'Inactivo'.
     * 
     * Un array de proveedores inactivos en formato asociativo.
     * Si ocurre un error en la consulta SQL.
     */
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

    /**
     * Obtiene todos los proveedores.
     * 
     * Ejecuta una consulta SQL para obtener todos los proveedores sin filtrar por estado.
     * 
     * Un array de todos los proveedores en formato asociativo.
     * Si ocurre un error en la consulta SQL.
     */
    static public function BLgetProvidersAll()
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT * FROM proveedores";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $proveedores;
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene contactos de un proveedor específico por ID.
     * 
     * Ejecuta una consulta SQL para obtener todos los contactos asociados a un proveedor
     * filtrados por el ID del proveedor y estado del contacto 'Activo'.
     * 
     * $idProveedor El ID del proveedor para obtener sus contactos.
     * Un array de contactos en formato asociativo.
     */
    static public function BLgetProvidersbyId($idProveedor)
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT c.*, t.tipoContacto
                    FROM contactoproveedor as c
                    LEFT JOIN tipocontacto as t ON c.idTipoContacto = t.idTipoContacto
                    WHERE c.idProveedor = :idProveedor
                    AND c.estadoContacto = 'Activo'";

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

    /**
     * Bloquea (marca como inactivo) un proveedor por ID.
     * 
     * Ejecuta una consulta SQL para actualizar el estado del proveedor a 'Inactivo'.
     * 
     * $idProveedor El ID del proveedor a bloquear.
     * Si ocurre un error en la consulta SQL.
     */
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

    /**
     * Desbloquea (marca como activo) un proveedor por ID.
     * 
     * Ejecuta una consulta SQL para actualizar el estado del proveedor a 'Activo'.
     * 
     * $idProveedor El ID del proveedor a desbloquear.
     * Si ocurre un error en la consulta SQL.
     */
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

    /**
     * Marca un proveedor como eliminado (cambia el estado a 'Eliminado') por ID.
     * 
     * Ejecuta una consulta SQL para actualizar el estado del proveedor a 'Eliminado'.
     * 
     * $idProveedor El ID del proveedor a eliminar.
     * Si ocurre un error en la consulta SQL.
     */
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

    /**
     * Inserta un nuevo proveedor en la base de datos.
     * 
     * Ejecuta una consulta SQL para insertar un nuevo proveedor con los datos proporcionados.
     * 
     * $datos Un array asociativo con los datos del proveedor a insertar.
     * Si ocurre un error en la consulta SQL.
     */
    static public function BLpostInsertProviders($datos)
    {
        try {
            $conn = self::getConnection();

            $sql = "INSERT INTO proveedores(nombreEmpresa, direccionProveedor, ciudad, estado, codigoPostal, pais, fechaRegistro, informacionProveedor, logo,
                    tipoProveedor, notas, fechaUltimaActividad, estadoProveedor, sitioWeb, rfc, tipoBanco, cuentaBancaria)
                    VALUES(:nombreEmpresa, :direccionProveedor, :ciudad, :estado, :codigoPostal, :pais, :fechaRegistro, :informacionProveedor, :logo,
                    :tipoProveedor, :notas, :fechaUltimaActividad, :estadoProveedor, :sitioWeb, :rfc, :tipoBanco, :cuentaBancaria);";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':nombreEmpresa', $datos['nombreEmpresa']);
            $stmt->bindParam(':direccionProveedor', $datos['direccionProveedor']);
            $stmt->bindParam(':ciudad', $datos['ciudad']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':codigoPostal', $datos['codigoPostal']);
            $stmt->bindParam(':pais', $datos['pais']);

            $stmt->bindParam(':fechaRegistro', $datos['fechaRegistro']);
            $stmt->bindParam(':informacionProveedor', $datos['informacionProveedor']);
            $stmt->bindParam(':logo', $datos['logo']);
            $stmt->bindParam(':tipoProveedor', $datos['tipoProveedor']);
            $stmt->bindParam(':notas', $datos['notas']);
            $stmt->bindParam(':fechaUltimaActividad', $datos['fechaUltimaActividad']);
            $stmt->bindParam(':estadoProveedor', $datos['estadoProveedor']);
            $stmt->bindParam(':sitioWeb', $datos['sitioWeb']);
            $stmt->bindParam(':rfc', $datos['rfc']);
            $stmt->bindParam(':tipoBanco', $datos['tipoBanco']);
            $stmt->bindParam(':cuentaBancaria', $datos['cuentaBancaria']);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al agregar Proveedor ' . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de un proveedor existente.
     * 
     * Ejecuta una consulta SQL para actualizar los datos del proveedor con la ID especificada.
     * 
     * $datos Un array asociativo con los datos del proveedor a actualizar. Debe incluir 'idProveedor'.
     * Si ocurre un error en la consulta SQL.
     */
    static public function BLpostUpdate($datos)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE proveedores SET nombreEmpresa = :nombreEmpresa, direccionProveedor = :direccionProveedor, ciudad = :ciudad, estado = :estado,
                    codigoPostal = :codigoPostal, pais = :pais, informacionProveedor = :informacionProveedor, logo = :logo, 
                    tipoProveedor = :tipoProveedor, notas = :notas, fechaUltimaActividad = :fechaUltimaActividad, sitioWeb = :sitioWeb, rfc = :rfc, 
                    tipoBanco = :tipoBanco, cuentaBancaria = :cuentaBancaria WHERE idProveedor = :idProveedor;";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':nombreEmpresa', $datos['nombreEmpresa']);
            $stmt->bindParam(':direccionProveedor', $datos['direccionProveedor']);
            $stmt->bindParam(':ciudad', $datos['ciudad']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':codigoPostal', $datos['codigoPostal']);
            $stmt->bindParam(':pais', $datos['pais']);
            $stmt->bindParam(':informacionProveedor', $datos['informacionProveedor']);
            $stmt->bindParam(':logo', $datos['logo']);
            $stmt->bindParam(':tipoProveedor', $datos['tipoProveedor']);
            $stmt->bindParam(':notas', $datos['notas']);
            $stmt->bindParam(':fechaUltimaActividad', $datos['fechaUltimaActividad']);
            $stmt->bindParam(':sitioWeb', $datos['sitioWeb']);
            $stmt->bindParam(':rfc', $datos['rfc']);
            $stmt->bindParam(':tipoBanco', $datos['tipoBanco']);
            $stmt->bindParam(':cuentaBancaria', $datos['cuentaBancaria']);
            $stmt->bindParam(':idProveedor', $datos['idProveedor']);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar Colaborador ' . $e->getMessage());
        }
    }
}
