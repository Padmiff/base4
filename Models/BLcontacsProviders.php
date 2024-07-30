<?php
require_once "Connection.php"; //Conexion con la BD

class ContacsProviders
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
     * Inserta un nuevo proveedor en la base de datos.
     *
     * $datos Array asociativo con la información del nuevo proveedor.
     * El ID del proveedor insertado.
     * En caso de error al agregar el proveedor.
     */
    static public function BLpostInsertProvider($datos)
    {
        try {

            $conn = self::getConnection();

            $sql = "INSERT INTO contactoproveedor(idProveedor, nombreProveedor, apellidoPaterno, apellidoMaterno, email, notas)
                    VALUES(:idProveedor, :nombreProveedor, :apellidoPaterno, :apellidoMaterno, :email, :notas)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':idProveedor', $datos['idProveedor']);
            $stmt->bindParam(':nombreProveedor', $datos['nombreProveedor']);
            $stmt->bindParam(':apellidoPaterno', $datos['apellidoPaterno']);
            $stmt->bindParam(':apellidoMaterno', $datos['apellidoMaterno']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':notas', $datos['notas']);

            $stmt->execute();
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Error al agregar Contacto: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza la contraseña de un proveedor.
     *
     * $password La nueva contraseña en texto plano.
     * $idContacto El ID del proveedor cuya contraseña se actualizará.
     * En caso de error al actualizar la contraseña.
     */
    static public function BLpasswordContacto($password, $idContacto)
    {
        try {
            $conn = self::getConnection();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE contactoproveedor SET password = :password WHERE idContacto = :idContacto";
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
     * True si el correo ya está registrado y activo, false en caso contrario.
     * En caso de error en la consulta.
     */
    static public function BLverificarCorreoExistente($email)
    {
        try {
            $conn = self::getConnection();
            $sql = "SELECT COUNT(*) FROM contactoproveedor WHERE email = :email AND estadoContacto = 'Activo'";
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
     * Actualiza la información de un proveedor existente.
     *
     * $datos Array asociativo con la información actualizada del proveedor, incluyendo el ID.
     * En caso de error al actualizar el proveedor.
     */
    static public function BLpostUpdateContact($datos)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE contactoproveedor set idProveedor = :idProveedor, nombreProveedor = :nombreProveedor, apellidoPaterno = :apellidoPaterno, apellidoMaterno = :apellidoMaterno,
            email = :email, notas = :notas WHERE idContacto = :idContacto";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':idProveedor', $datos['idProveedor']);
            $stmt->bindParam(':nombreProveedor', $datos['nombreProveedor']);
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
     * Marca un proveedor como eliminado.
     *
     * $idContacto El ID del proveedor a eliminar.
     * En caso de error en la consulta.
     */
    static public function BLdeleteContacts($idContacto)
    {
        try {
            $conn = self::getConnection();

            $sql = "UPDATE contactoproveedor SET estadoContacto = 'Eliminado' WHERE idContacto = :idContacto";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idContacto', $idContacto, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Error en la consulta: ' . $e->getMessage());
        }
    }
}
