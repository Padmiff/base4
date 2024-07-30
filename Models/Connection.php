<?php

class Connection
{
    // Variable estática para almacenar la instancia de la conexión
    private static $conn = null;

    /**
     * Constructor privado para evitar la instanciación fuera de la clase.
     * 
     * Esto garantiza que la clase `Connection` no pueda ser instanciada directamente desde fuera de la clase.
     */
    private function __construct()
    {
        // Constructor privado
    }

    /**
     * Obtiene la instancia de conexión a la base de datos.
     * 
     * Si la conexión aún no está establecida, crea una nueva conexión utilizando los parámetros proporcionados.
     * 
     * La instancia de conexión a la base de datos.
     */
    public static function connectionBD()
    {
        // Verifica si la conexión ya ha sido establecida
        if (!self::$conn) {
            $host = 'localhost'; // Dirección del servidor de base de datos
            $dbname = 'base4';   // Nombre de la base de datos
            $username = 'root';  // Nombre de usuario de la base de datos
            $password = '';      // Contraseña de la base de datos

            // Cadena de conexión DSN para PDO
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            try {
                // Crea una nueva instancia de PDO para la conexión a la base de datos
                self::$conn = new PDO($dsn, $username, $password);
                // Configurar el manejo de errores de PDO
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Manejar error de conexión como mejor convenga tu aplicación
                die('Error de conexión: ' . $e->getMessage());
            }
        }

        // Devuelve la instancia de conexión a la base de datos
        return self::$conn;
    }
}
