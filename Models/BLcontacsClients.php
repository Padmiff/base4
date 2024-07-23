<?php
require_once "Connection.php";

class ContacsClients
{

    private static $conn;

    private static function getConnection()
    {
        if (!isset(self::$conn)) {
            self::$conn = Connection::connectionBD();
        }
        return self::$conn;
    }

    static public function BLpostInsertContactos($datos)
    {
        try {
            if (self::BLverificarCorreoExistente($datos['email'])) {
                throw new Exception('El correo ya está registrado.');
            }

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
        } catch (PDOException $e) {
            throw new Exception('Error al agregar Contacto: ' . $e->getMessage());
        }
    }

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

    static public function obtenerIdUsuarioDesdeElCorreo($email)
    {
        $conn = self::getConnection();

        $sql = "SELECT idContacto FROM contactocliente WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['idContacto'];
        } else {
            throw new Exception("No se encontró ningún usuario con el correo electrónico proporcionado.");
        }
    }

    static public function BLverificarCorreoExistente($email)
    {
        try {
            $conn = self::getConnection();
            $sql = "SELECT COUNT(*) FROM contactocliente WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (PDOException $e) {
            throw new Exception('Error al verificar el correo: ' . $e->getMessage());
        }
    }

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
