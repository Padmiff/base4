<?php

class GraphicsController
{
    /**
     * Obtiene los datos para la gráfica general.
     * 
     * Datos necesarios para la gráfica.
     * Si ocurre un error al obtener los datos.
     */
    public static function obtenerDatosGrafica()
    {
        try {
            // Obtener los datos para la gráfica desde el modelo
            $datos = DatosModel::obtenerDatosGrafica();
            return $datos;
        } catch (Exception $e) {
            // Manejo de errores
            throw new Exception('Error al obtener los datos para la gráfica: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de inactivos para la gráfica.
     * 
     * Datos de inactivos necesarios para la gráfica.
     * Si ocurre un error al obtener los datos inactivos.
     */
    public static function obtenerDatosGraficaInactivos()
    {
        try {
            // Obtener los datos de inactivos para la gráfica desde el modelo
            $datosInactivos = DatosModel::obtenerDatosGraficaInactivos();
            return $datosInactivos;
        } catch (Exception $e) {
            // Manejo de errores
            throw new Exception('Error al obtener los datos inactivos para la gráfica: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene los datos de género para la intranet.
     * 
     * Datos de género necesarios para la intranet.
     * Si ocurre un error al obtener los datos de género.
     */
    public static function obtenerDatosGeneroIntranet()
    {
        try {
            // Obtener los datos de género para la intranet desde el modelo
            $datosGenero = DatosModel::obtenerDatosGeneroIntranet();
            return $datosGenero;
        } catch (Exception $e) {
            // Manejo de errores
            throw new Exception('Error al obtener los datos de género para la intranet: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene los datos por tipo de incidencia y los devuelve en formato JSON.
     * 
     * Imprime los datos en formato JSON o un mensaje de error en formato JSON.
     */
    public function obtenerDatosPorTipo()
    {
        try {
            // Llamar al método del modelo para obtener los datos
            $datos = DatosModel::obtenerDatosPorTipoIncidencia();

            // Devolver los datos como JSON
            header('Content-Type: application/json');
            echo json_encode($datos);
        } catch (Exception $e) {
            // Manejar excepciones y devolver un error en formato JSON
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
        }
    }
}
