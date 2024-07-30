<?php

class FileController
{
    /**
     * Sube un archivo asociado a un empleado.
     * 
     *  $file Información del archivo a subir (debe contener datos como el nombre, tipo y contenido).
     *  $idEmpleado ID del empleado al que se asociará el archivo.
     *  Retorna verdadero si el archivo se subió correctamente, falso en caso contrario.
     */
    public static function subirArchivo($file, $idEmpleado)
    {
        // Llama al método BLsubirArchivo del modelo BLfiles pasando el archivo y el id del empleado
        if (BLfiles::BLsubirArchivo($file, $idEmpleado)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Elimina un archivo del sistema.
     * 
     * $idArchivo ID del archivo a eliminar.
     * Retorna verdadero si el archivo se eliminó correctamente, falso en caso contrario.
     */
    public static function eliminarArchivo($idArchivo)
    {
        // Llama al método BLeliminarArchivo del modelo BLfiles pasando el id del archivo
        if (BLfiles::BLeliminarArchivo($idArchivo)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtiene la lista de archivos asociados a un empleado específico.
     * 
     * $idEmpleado ID del empleado para el cual se desean obtener los archivos.
     * Lista de archivos asociados al empleado.
     */
    public static function obtenerArchivosPorEmpleado($idEmpleado)
    {
        try {
            // Llama al método BLobtenerArchivosPorEmpleado del modelo BLfiles pasando el id del empleado
            $archivos = BLfiles::BLobtenerArchivosPorEmpleado($idEmpleado);
            return $archivos;
        } catch (PDOException $e) {
            // Manejo de errores específico para PDOException
            echo 'Error al obtener los archivos: ' . $e->getMessage();
            return []; // Retorna un array vacío en caso de error
        }
    }
}
