<?php

class ProvidersController
{
    /**
     * Obtiene una lista de proveedores activos.
     * 
     * Lista de proveedores activos.
     */
    static public function getUsersActive()
    {
        try {
            $proveedoresActivos = BLproviders::getProvidersActive();  // Llama al método de la capa de negocios

            return $proveedoresActivos;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            // Manejo de errores
            echo 'Error al obtener proveedores activos: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene una lista de proveedores inactivos.
     * 
     * Lista de proveedores inactivos.
     */
    static public function getUsersInactive()
    {
        try {
            $proveedoresInactivos = BLproviders::getProvidersInactive();  // Llama al método de la capa de negocios

            return $proveedoresInactivos;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            // Manejo de errores
            echo 'Error al obtener proveedores Bloqueados: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene todos los proveedores.
     * 
     * Lista de todos los proveedores.
     */
    static public function getProvidersAll()
    {
        try {
            $proveedores = BLproviders::BLgetProvidersAll();

            return $proveedores;
        } catch (PDOException $e) {
            echo 'Error al obtener proveedores: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene un proveedor específico por su ID.
     * 
     * $idProveedor ID del proveedor que se desea obtener.
     * Datos del proveedor.
     */
    static public function getProvidersbyId($idProveedor)
    {
        try {
            $proveedor = BLproviders::BLgetProvidersbyId($idProveedor);
            // Puedes devolver los resultados para ser utilizados en una vista
            return $proveedor;
        } catch (PDOException $e) {
            echo 'Error al obtener proveedores: ' . $e->getMessage();
        }
    }

    /**
     * Bloquea a un proveedor.
     * 
     * $idProveedor ID del proveedor que se desea bloquear.
     */
    static public function blockprovider($idProveedor)
    {
        try {
            BLproviders::BLblockprovider($idProveedor);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al bloquear proveedor: ' . $e->getMessage();
        }
    }

    /**
     * Desbloquea a un proveedor.
     * 
     * $idProveedor ID del proveedor que se desea desbloquear.
     */
    static public function unlockprovider($idProveedor)
    {
        try {
            BLproviders::BLunlockprovider($idProveedor);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al desbloquear proveedor: ' . $e->getMessage();
        }
    }

    /**
     * Elimina un proveedor del sistema.
     * 
     * $idProveedor ID del proveedor que se desea eliminar.
     */
    static public function deleteprovider($idProveedor)
    {
        try {
            BLproviders::BLdeleteprovider($idProveedor);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al eliminar proveedor: ' . $e->getMessage();
        }
    }

    /**
     * Inserta un nuevo proveedor en el sistema.
     * 
     * Redirige a la página de proveedores o muestra un mensaje de error.
     */
    static public function postInsertProveedores()
    {
        if (isset($_POST['registrar'])) {
            $datos = [
                'nombreEmpresa' => $_POST['nombreEmpresa'],
                'direccionProveedor' => $_POST['direccionProveedor'],
                'ciudad' => $_POST['ciudad'],
                'estado' => $_POST['estado'],
                'codigoPostal' => $_POST['codigoPostal'],
                'pais' => $_POST['pais'],
                'fechaRegistro' => $_POST['fechaRegistro'],
                'informacionProveedor' => $_POST['informacionProveedor'],
                'logo' => $_POST['logo'],
                'tipoProveedor' => $_POST['tipoProveedor'],
                'notas' => $_POST['notas'],
                'fechaUltimaActividad' => $_POST['fechaUltimaActividad'],
                'estadoProveedor' => $_POST['estadoProveedor'],
                'sitioWeb' => $_POST['sitioWeb'],
                'rfc' => $_POST['rfc'],
                'tipoBanco' => $_POST['tipoBanco'],
                'cuentaBancaria' => $_POST['cuentaBancaria'],
            ];
            try {
                // Llamar a la función del modelo para insertar en la base de datos
                BLproviders::BLpostInsertProviders($datos);
                // Redirigir o mostrar un mensaje de éxito
                echo '<script>window.location.href = "Proveedores";</script>';
                exit;
            } catch (Exception $e) {
                // Manejar el error, mostrar un mensaje al usuario, registrar el error, etc.
                echo "Error: " . $e->getMessage();
            }
        }
    }

    /**
     * Actualiza los datos de un proveedor existente.
     * 
     * Redirige a la página de proveedores o muestra un mensaje de error.
     */
    static public function postUpdateProveedores()
    {
        if (isset($_POST['actualizar'])) {
            $datos = [
                'idProveedor' => $_POST['idProveedor'],
                'nombreEmpresa' => $_POST['nombreEmpresa'],
                'direccionProveedor' => $_POST['direccionProveedor'],
                'ciudad' => $_POST['ciudad'],
                'estado' => $_POST['estado'],
                'codigoPostal' => $_POST['codigoPostal'],
                'pais' => $_POST['pais'],
                'informacionProveedor' => $_POST['informacionProveedor'],
                'logo' => $_POST['logo'],
                'tipoProveedor' => $_POST['tipoProveedor'],
                'notas' => $_POST['notas'],
                'fechaUltimaActividad' => $_POST['fechaUltimaActividad'],
                'estadoProveedor' => $_POST['estadoProveedor'],
                'sitioWeb' => $_POST['sitioWeb'],
                'rfc' => $_POST['rfc'],
                'tipoBanco' => $_POST['tipoBanco'],
                'cuentaBancaria' => $_POST['cuentaBancaria'],
            ];
            try {
                // Llamar a la función del modelo para insertar en la base de datos
                BLproviders::BLpostUpdate($datos);
                // Redirigir o mostrar un mensaje de éxito
                echo '<script>window.location.href = "Proveedores";</script>';
                exit;
            } catch (Exception $e) {
                // Manejar el error, mostrar un mensaje al usuario, registrar el error, etc.
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
