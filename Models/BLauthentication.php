<?php
require_once "Connection.php";

class BLauthentication
{
    public static function BLlogin($email, $password)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            $sql = 'SELECT * FROM empleado WHERE emailEmpleado = :email';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['hashed_password'])) {
                session_start();
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['idEmpleado'];
                $_SESSION['email'] = $user['emailEmpleado'];
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Error en la consulta de login: ' . $e->getMessage());
            return false;
        }
    }

    public static function BLregisterUser($email, $nombre, $password)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            if (self::emailExistsInDatabase($email)) {
                return "El correo electrónico ya está registrado.";
            }

            if (!self::emailExistsOnWeb($email)) {
                return "El correo electrónico no existe o es inválido.";
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO empleado (emailEmpleado, nombreEmpleado, hashed_password) VALUES (:email, :nombre, :hashed_password)';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email, 'nombre' => $nombre, 'hashed_password' => $hashed_password]);

            return true;
        } catch (PDOException $e) {
            error_log('Error en la consulta de registro: ' . $e->getMessage());
            return "Error en la consulta de registro.";
        }
    }

    private static function emailExistsInDatabase($email)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            $sql = 'SELECT COUNT(*) AS count FROM empleado WHERE emailEmpleado = :email';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch();

            return ($result['count'] > 0);
        } catch (PDOException $e) {
            error_log('Error en la consulta de verificación de correo electrónico: ' . $e->getMessage());
            return false;
        }
    }

    private static function emailExistsOnWeb($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = substr(strrchr($email, "@"), 1);

        $mxhosts = [];
        if (!checkdnsrr($domain, "MX")) {
            return false;
        }

        return true;
    }
}
