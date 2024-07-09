<?php

class FileController
{

    public static function subirArchivo($file)
    {
        if (BLfiles::BLsubirArchivo($file)) {
            return true;
        } else {
            return false;
        }
    }

    public static function eliminarArchivo($id)
    {
        if (BLfiles::BLeliminarArchivo($id)) {
            return true;
        } else {
            return false;
        }
    }

    public static function obtenerArchivos()
    {
        try {
            $archivos = BLfiles::BLobtenerArchivos();
            return $archivos;
        } catch (PDOException $e) {
            echo 'Error al obtener los archivos: ' . $e->getMessage();
        }
    }
}
