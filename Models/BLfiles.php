<?php
require_once "Connection.php";
class BLfiles
{
    static public function BLsubirArchivo($file)
    {
        $conn = Connection::connectionBD();

        $nombreArchivo = $file['name'];
        $tipoArchivo = $file['type'];
        $rutaTemporal = $file['tmp_name'];
        $directorioDestino = "Views/Pages/Archivos/files/" . $nombreArchivo;

        if (move_uploaded_file($rutaTemporal, $directorioDestino)) {
            try {
                $sql = "INSERT INTO archivos (nombre, tipo, ruta, fecha_subida) VALUES (:nombre, :tipo, :ruta, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombreArchivo, PDO::PARAM_STR);
                $stmt->bindParam(':tipo', $tipoArchivo, PDO::PARAM_STR);
                $stmt->bindParam(':ruta', $directorioDestino, PDO::PARAM_STR);
                $stmt->execute();

                return "Archivo subido exitosamente";
            } catch (PDOException $e) {
                return "Error al subir el archivo: " . $e->getMessage();
            }
        } else {
            return "Error al mover el archivo";
        }
    }

    static public function BLeliminarArchivo($id)
    {
        $conn = Connection::connectionBD();

        try {
            $sql = "DELETE FROM archivos WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return "Archivo eliminado exitosamente";
        } catch (PDOException $e) {
            return "Error al eliminar el archivo: " . $e->getMessage();
        }
    }

    static public function BLobtenerArchivos()
    {
        $conn = Connection::connectionBD();

        try {
            $sql = "SELECT * FROM archivos";
            $stmt = $conn->query($sql);
            $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $archivos;
        } catch (PDOException $e) {
            echo 'Error en la consulta: ' . $e->getMessage();
            return [];
        }
    }
}
