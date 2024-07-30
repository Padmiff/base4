<?php
require_once dirname(__DIR__) . '/Models/BLauthentication.php';



class AuthenticationController
{
    /**
     * Inicia sesión con el correo electrónico y la contraseña proporcionados.
     * 
     *  $email El correo electrónico del usuario.
     *  $password La contraseña del usuario.
     * bool true si el inicio de sesión fue exitoso, false en caso contrario.
     */
    public static function login($email, $password)
    {
        // Llamar al método de autenticación de BLauthentication
        $login = BLauthentication::BLlogin($email, $password);

        // Verificar el resultado del inicio de sesión
        if ($login === true) {
            // Si el inicio de sesión fue exitoso
            return true;
        } else {
            // Si el inicio de sesión falló
            return false;
        }
    }

    /**
     * Registra un nuevo usuario con el correo electrónico, nombre y contraseña proporcionados.
     * 
     *  $email El correo electrónico del usuario.
     *  $nombre El nombre del usuario.
     *  $password La contraseña del usuario.
     *  bool true si el registro fue exitoso, false en caso de error.
     */
    public static function registerUser($email, $nombre, $password)
    {
        // Llamar al método de registro de usuario de BLauthentication
        $result = BLauthentication::BLregisterUser($email, $nombre, $password);

        // Verificar el resultado del registro de usuario
        if ($result === true) {
            // Si el registro fue exitoso
            return true;
        } else {
            // Si hubo algún error en el registro
            return false;
        }
    }

    /**
     * Obtiene el número de conexiones diarias.
     * 
     * return int El número de conexiones diarias.
     * Exception Si ocurre un error al obtener las conexiones diarias.
     */
    public static function obtenerConexionesDiarias()
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            $fechaHoy = date('Y-m-d'); // Fecha actual

            $sql = 'SELECT COUNT(*) AS conexiones_diarias
                    FROM RegistroActividad
                    WHERE fecha = :fechaHoy';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['fechaHoy' => $fechaHoy]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return $resultado['conexiones_diarias'];
        } catch (PDOException $e) {
            error_log('Error al obtener conexiones diarias: ' . $e->getMessage());
            throw new Exception('Error al obtener conexiones diarias.');
        }
    }
}
