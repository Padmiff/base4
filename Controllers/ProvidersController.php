<?php

class ProvidersController
{
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

    static public function getProvidersAll()
    {
        try {
            $proveedores = BLproviders::BLgetProvidersAll();

            return $proveedores;
        } catch (PDOException $e) {
            echo 'Error al obtener proveedores: ' . $e->getMessage();
        }
    }

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

    static public function blockprovider($idProveedor)
    {
        try {
            BLproviders::BLblockprovider($idProveedor);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al bloquear proveedor: ' . $e->getMessage();
        }
    }

    static public function unlockprovider($idProveedor)
    {
        try {
            BLproviders::BLunlockprovider($idProveedor);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al desbloquear proveedor: ' . $e->getMessage();
        }
    }

    static public function deleteprovider($idProveedor)
    {
        try {
            BLproviders::BLdeleteprovider($idProveedor);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al eliminar proveedor: ' . $e->getMessage();
        }
    }
}
