<?php

class ClientsController
{
    static public function getUsersActive()
    {
        try {
            $clienteActivos = BLclients::getClientActive();  // Llama al método de la capa de negocios

            return $clienteActivos;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            // Manejo de errores
            echo 'Error al obtener clientes activos: ' . $e->getMessage();
        }
    }

    static public function getUsersInactive()
    {
        try {
            $clienteInactivos = BLclients::getClientInactive();  // Llama al método de la capa de negocios

            return $clienteInactivos;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            // Manejo de errores
            echo 'Error al obtener clientes Bloqueados: ' . $e->getMessage();
        }
    }

    static public function getClientAll()
    {

        $clientes = BLclients::BLgetClientAll();  // Llama al método de la capa de negocios

        return $clientes;  // Retorna los empleados activos obtenidos

    }

    static public function getClientbyId($idCliente)
    {

        $cliente = BLclients::BLgetClientbyId($idCliente);  // Llama al método de la capa de negocios

        return $cliente;  // Retorna los empleados activos obtenidos

    }

    static public function blockclient($idCliente)
    {
        try {
            BLclients::BLblockclient($idCliente);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al bloquear cliente: ' . $e->getMessage();
        }
    }

    static public function unlockclient($idCliente)
    {
        try {
            BLclients::BLunlockclient($idCliente);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al desbloquear cliente: ' . $e->getMessage();
        }
    }

    static public function deleteclient($idCliente)
    {
        try {
            BLclients::BLdeleteclient($idCliente);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al eliminar cliente: ' . $e->getMessage();
        }
    }
}
