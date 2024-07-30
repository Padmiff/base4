<?php
require_once "Connection.php"; //Conexion con la BD

class ContacsClients
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
     * Inserta un nuevo contacto en la base de datos.
     *
     * $datos Array asociativo con la información del nuevo contacto.
     * El ID del contacto insertado.
     * En caso de error al agregar el contacto.
     */
    static public function BLpostInsertContactos($datos)
    {
        try {

            $conn = self::getConnection();

            $sql = "INSERT INTO contactocliente(idCliente, nombre, apellidoPaterno, apellidoMaterno, email, notas, estadoContacto)
                    VALUES(:idCliente, :nombre, :apellidoPaterno, :apellidoMaterno, :email, :notas, :estadoContacto)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':idCliente', $datos['idCliente']);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellidoPaterno', $datos['apellidoPaterno']);
            $stmt->bindParam(':apellidoMaterno', $datos['apellidoMaterno']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':notas', $datos['notas']);
            $stmt->bindParam(':estadoContacto', $datos['estadoContacto']);

            $stmt->execute();
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Error al agregar Contacto: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la contraseña de un contacto.
     *
     * $password La nueva contraseña en texto plano.
     * $idContacto El ID del contacto cuya contraseña se actualizará.
     * En caso de error al actualizar la contraseña.
     */
    static public function BLpasswordContacto($password, $idContacto)
    {
        try {
            $conn = self::getConnection();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE contactocliente SET password = :password WHERE idContacto = :idContacto";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':idContacto', $idContacto, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar la contraseña: " . $e->getMessage());
            throw new Exception('Error al ingresar contraseña ' . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->closeCursor();
            }
        }
    }

    /**
     * Verifica si un correo electrónico ya está registrado en la base de datos.
     *
     * $email El correo electrónico a verificar.
     * True si el correo ya está registrado, false en caso contrario.
     * En caso de error en la consulta.
     */
    static public function BLverificarCorreoExistente($email)
    {
        try {
            $conn = self::getConnection();
            $sql = "SELECT COUNT(*) FROM contactocliente WHERE email = :email AND estadoContacto = 'Activo'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn() > 0;

            return $count;
        } catch (PDOException $e) {
            throw new Exception('Error al verificar el correo: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la información de un contacto existente.
     *
     * $datos Array asociativo con la información actualizada del contacto, incluyendo el ID.
     * En caso de error al actualizar el contacto.
     */
    static public function BLpostUpdateContact($datos)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE contactocliente set idCliente = :idCliente, nombre = :nombre, apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno,
            email = :email, notas = :notas WHERE idContacto = :idContacto";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':idCliente', $datos['idCliente']);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellidoPaterno', $datos['apellidoPaterno']);
            $stmt->bindParam(':apellidoMaterno', $datos['apellidoMaterno']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':notas', $datos['notas']);
            $stmt->bindParam(':idContacto', $datos['idContacto']);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar Colaborador ' . $e->getMessage());
        }
    }

    /**
     * Marca un contacto como eliminado.
     *
     * $idContacto El ID del contacto a eliminar.
     * En caso de error en la consulta.
     */
    static public function BLdeleteContacts($idContacto)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE contactocliente SET estadoContacto = 'Eliminado' WHERE idContacto = :idContacto";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idContacto', $idContacto, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }
}
