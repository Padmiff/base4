<?php
require_once "Connection.php"; //Conexion con la BD

class BLclients
{
    private static $conn;

    /**
     * Obtiene una instancia de conexión a la base de datos.
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
     * Obtiene todos los clientes activos.
     *
     * Array asociativo de clientes activos.
     * En caso de error en la consulta.
     */
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
    }

    /**
     * Obtiene todos los clientes inactivos.
     *
     * Array asociativo de clientes inactivos.
     * En caso de error en la consulta.
     */
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

    /**
     * Obtiene todos los clientes.
     *
     * Array asociativo de todos los clientes.
     */
    static public function BLgetClientAll()
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT * FROM client ";

            $stmt = $conn->query($sql);
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $clientes;
        } catch (PDOException $e) {
            // Manejo de errores de PDO
            echo 'Error en la consulta: ' . $e->getMessage();
            return [];
        }
    }

    /**
     * Obtiene la información de un cliente específico por su ID.
     *
     * $idCliente El ID del cliente.
     * Array asociativo con la información del cliente.
     */
    static public function BLgetClientbyId($idCliente)
    {
        try {
            $conn = self::getConnection();

            $sql = "SELECT c.*, t.tipoContacto
                    FROM contactocliente AS c
                    LEFT JOIN tipocontactocliente AS t ON c.idTipoContato = t.idTipoContacto
                    WHERE c.idCliente = :idCliente
                    AND c.estadoContacto = 'Activo'";

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

    /**
     * Cambia el estado de cuenta de un cliente a 'Inactivo'.
     *
     * $idCliente El ID del cliente a bloquear.
     * En caso de error en la consulta.
     */
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

    /**
     * Cambia el estado de cuenta de un cliente a 'Activo'.
     *
     * $idCliente El ID del cliente a desbloquear.
     * En caso de error en la consulta.
     */
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

    /**
     * Cambia el estado de cuenta de un cliente a 'Eliminado'.
     *
     * $idCliente El ID del cliente a eliminar.
     * Exception En caso de error en la consulta.
     */
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

    /**
     * Inserta un nuevo cliente en la base de datos.
     *
     * $datos Array asociativo con la información del nuevo cliente.
     * Exception En caso de error al agregar el cliente.
     */
    static public function BLpostInsertClients($datos)
    {
        try {
            $conn = self::getConnection();

            $sql = "INSERT INTO client(nombreCliente, apellidoPaterno, apellidoMaterno, direccion, ciudad, estado, 
                    codigoPostal, pais, fechaRegistro, informacion, tipoCliente, preferencia, notas, fechaUltimaActividad, 
                    estadoCuenta, fechaNacimiento, foto)VALUES(:nombre, :apellidoPaterno, :apellidoMaterno, :direccion, :ciudad,
                    :estado, :codigoPostal, :pais, :fechaRegistro, :informacion, :tipoCliente, :preferencia, :notas, :fechaUltimaActividad,
                    :estadoCuenta, :fechaNacimiento, :foto)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellidoPaterno', $datos['apellidoPaterno']);
            $stmt->bindParam(':apellidoMaterno', $datos['apellidoMaterno']);
            $stmt->bindParam(':direccion', $datos['direccion']);
            $stmt->bindParam(':ciudad', $datos['ciudad']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':codigoPostal', $datos['codigoPostal']);
            $stmt->bindParam(':pais', $datos['pais']);

            $stmt->bindParam(':fechaRegistro', $datos['fechaRegistro']);
            $stmt->bindParam(':informacion', $datos['informacion']);
            $stmt->bindParam(':tipoCliente', $datos['tipoCliente']);
            $stmt->bindParam(':preferencia', $datos['preferencia']);
            $stmt->bindParam(':notas', $datos['notas']);
            $stmt->bindParam(':fechaUltimaActividad', $datos['fechaUltimaActividad']);
            $stmt->bindParam(':estadoCuenta', $datos['estadoCuenta']);
            $stmt->bindParam(':fechaNacimiento', $datos['fechaNacimiento']);
            $stmt->bindParam(':foto', $datos['foto']);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al agregar Cliente ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la información de un cliente existente.
     *
     * $datos Array asociativo con la información actualizada del cliente, incluyendo el ID.
     * En caso de error al actualizar el cliente.
     */
    static public function BLpostUpdate($datos)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE client SET nombreCliente = :nombreCliente,  apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno, direccion = :direccion,
                    ciudad = :ciudad, estado = :estado, codigoPostal = :codigoPostal, pais = :pais, informacion = :informacion, tipoCliente = :tipoCliente,
                    preferencia = :preferencia, notas = :notas, fechaUltimaActividad = :fechaUltimaActividad, fechaNacimiento = :fechaNacimiento, foto = :foto WHERE idCliente = :idCliente";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':nombreCliente', $datos['nombreCliente']);
            $stmt->bindParam(':apellidoPaterno', $datos['apellidoPaterno']);
            $stmt->bindParam(':apellidoMaterno', $datos['apellidoMaterno']);
            $stmt->bindParam(':direccion', $datos['direccion']);
            $stmt->bindParam(':ciudad', $datos['ciudad']);
            $stmt->bindParam(':estado', $datos['estado']);
            $stmt->bindParam(':codigoPostal', $datos['codigoPostal']);
            $stmt->bindParam(':pais', $datos['pais']);
            $stmt->bindParam(':informacion', $datos['informacion']);
            $stmt->bindParam(':tipoCliente', $datos['tipoCliente']);
            $stmt->bindParam(':preferencia', $datos['preferencia']);
            $stmt->bindParam(':notas', $datos['notas']);
            $stmt->bindParam(':fechaUltimaActividad', $datos['fechaUltimaActividad']);
            $stmt->bindParam(':fechaNacimiento', $datos['fechaNacimiento']);
            $stmt->bindParam(':foto', $datos['foto']);
            $stmt->bindParam(':idCliente', $datos['idCliente']);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar Colaborador ' . $e->getMessage());
        }
    }
}
