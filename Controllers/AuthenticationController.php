<?php
include '../Models/BLauthentication.php';
class AuthenticationController
{
    public static function login($email, $password)
    {
        $login = BLauthentication::BLlogin($email, $password);
        if ($login) {
            return true;
        } else {
            return false;
        }
    }

    public static function registerUser($email, $nombre, $password)
    {
        $result = BLauthentication::BLregisterUser($email, $nombre, $password);
        if ($result === true) {
            // Registro exitoso, redirigir o responder de acuerdo a la lógica de tu aplicación
            return true;
        } else {
            return false;
        }
    }
}
