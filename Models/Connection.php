<?php

class Connection
{
    // static public function connectionBD()
    // {
    //     $host = 'localhost';
    //     $user = 'root';
    //     $pass = '';
    //     $db = 'base4';

    //     try {
    //         $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8', $user, $pass);
    //         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //         return $pdo;  // Devuelve el objeto PDO creado
    //     } catch (PDOException $e) {
    //         throw new Exception('Error de conexión: ' . $e->getMessage());
    //     }
    // }

    private static $conn = null;

    private function __construct()
    {
        // Constructor privado para evitar instanciación fuera de la clase
    }

    public static function connectionBD()
    {
        if (!self::$conn) {
            $host = 'localhost';
            $dbname = 'base4';
            $username = 'root';
            $password = '';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            try {
                self::$conn = new PDO($dsn, $username, $password);
                // Configurar el manejo de errores de PDO
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Manejar error de conexión como mejor convenga tu aplicación
                die('Error de conexión: ' . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
