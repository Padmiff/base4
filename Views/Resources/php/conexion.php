<?php

class Connection
{
    static public function connectionBD()
    {
        $host = 'localhost';     // Quité el puerto :3306 si no es necesario
        $user = 'root';      // Corregí el espacio antes de 'base4_ho'
        $pass = '';
        $db = 'base4';

        try {
            $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8', $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;  // Devuelve el objeto PDO creado
        } catch (PDOException $e) {
            throw new Exception('Error de conexión: ' . $e->getMessage());
        }
    }
}
