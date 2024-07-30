<?php
require_once "Connection.php";

class BLfiles
{
    /**
     * Subir un archivo al servidor y registrar su información en la base de datos.
     * 
     * $file Información del archivo subido ($_FILES['archivo']).
     * $idEmpleado ID del empleado al que se asociará el archivo.
     * Mensaje de éxito o error.
     */
    static public function BLsubirArchivo($file, $idEmpleado)
    {
        $conn = Connection::connectionBD();

        $nombreArchivo = $file['name'];
        $tipoArchivo = $file['type'];
        $rutaTemporal = $file['tmp_name'];

        // Directorio de destino específico para cada usuario
        $directorioBase = "Views/Pages/Archivos/files/";
        $directorioDestino = $directorioBase . "usuario_" . $idEmpleado . "/";

        // Crear directorio si no existe
        if (!file_exists($directorioDestino)) {
            mkdir($directorioDestino, 0777, true); // Crear directorio recursivamente
        }

        $rutaFinal = $directorioDestino . $nombreArchivo;

        if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
            try {
                $sql = "INSERT INTO archivos (nombre, tipo, ruta, fecha_subida, idEmpleado) 
                        VALUES (:nombre, :tipo, :ruta, NOW(), :idEmpleado)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombreArchivo, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $tipoArchivo, PDO::PARAM_STR);
                $stmt->bindParam(':ruta', $rutaFinal, PDO::PARAM_STR);
                $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
                $stmt->execute();

                return "Archivo subido exitosamente";
            } catch (PDOException $e) {
                return "Error al subir el archivo: " . $e->getMessage();
            }
        } else {
            return "Error al mover el archivo";
        }
    }

    /**
     * Eliminar un archivo del servidor y su registro en la base de datos.
     * 
     * $idArchivo ID del archivo a eliminar.
     * Mensaje de éxito o error.
     */
    static public function BLeliminarArchivo($idArchivo)
    {
        $conn = Connection::connectionBD();

        try {
            // Obtener la ruta del archivo para eliminar físicamente
            $sql = "SELECT ruta FROM archivos WHERE idArchivos = :idArchivo";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idArchivo', $idArchivo, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            // Eliminar archivo físicamente
            if ($resultado && file_exists($resultado['ruta'])) {
                unlink($resultado['ruta']);
            }

            // Eliminar registro de la base de datos
            $sqlDelete = "DELETE FROM archivos WHERE idArchivos = :idArchivo";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bindParam(':idArchivo', $idArchivo, PDO::PARAM_INT);
            $stmtDelete->execute();

            return "Archivo eliminado exitosamente";
        } catch (PDOException $e) {
            return "Error al eliminar el archivo: " . $e->getMessage();
        }
    }

    /**
     * Obtener una lista de archivos asociados a un empleado específico.
     * 
     * $idEmpleado ID del empleado cuyas archivos se desean obtener.
     * Lista de archivos asociados al empleado.
     */
    static public function BLobtenerArchivosPorEmpleado($idEmpleado)
    {
        $conn = Connection::connectionBD();

        try {
            $sql = "SELECT * FROM archivos WHERE idEmpleado = :idEmpleado";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
            $stmt->execute();
            $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $archivos;
        } catch (PDOException $e) {
            return [];
        }
    }
}
