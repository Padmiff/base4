<?php

class ClientsController
{
    static public function getUsersActive()
    {
        try {
            $clienteActivos = BLclients::getClientActive();

            return $clienteActivos;
        } catch (PDOException $e) {
            echo 'Error al obtener clientes activos: ' . $e->getMessage();
        }
    }

    static public function getUsersInactive()
    {
        try {
            $clienteInactivos = BLclients::getClientInactive();

            return $clienteInactivos;
        } catch (PDOException $e) {
            echo 'Error al obtener clientes Bloqueados: ' . $e->getMessage();
        }
    }

    static public function getClientAll()
    {

        $clientes = BLclients::BLgetClientAll();

        return $clientes;
    }

    static public function getClientbyId($idCliente)
    {

        $cliente = BLclients::BLgetClientbyId($idCliente);

        return $cliente;
    }

    static public function blockclient($idCliente)
    {
        try {
            BLclients::BLblockclient($idCliente);
        } catch (Exception $e) {
            echo 'Error al bloquear cliente: ' . $e->getMessage();
        }
    }

    static public function unlockclient($idCliente)
    {
        try {
            BLclients::BLunlockclient($idCliente);
        } catch (Exception $e) {
            echo 'Error al desbloquear cliente: ' . $e->getMessage();
        }
    }

    static public function deleteclient($idCliente)
    {
        try {
            BLclients::BLdeleteclient($idCliente);
        } catch (Exception $e) {
            echo 'Error al eliminar cliente: ' . $e->getMessage();
        }
    }

    static public function postInsertClientes()
    {
        if (isset($_POST['registrar'])) {
            $datos = [
                'nombre' => $_POST['nombre'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'direccion' => $_POST['direccion'],
                'ciudad' => $_POST['ciudad'],
                'estado' => $_POST['estado'],
                'codigoPostal' => $_POST['codigoPostal'],
                'pais' => $_POST['pais'],
                'fechaRegistro' => $_POST['fechaRegistro'],
                'informacion' => $_POST['informacion'],
                'tipoCliente' => $_POST['tipoCliente'],
                'preferencia' => $_POST['preferencia'],
                'notas' => $_POST['notas'],
                'fechaUltimaActividad' => $_POST['fechaUltimaActividad'],
                'estadoCuenta' => $_POST['estadoCuenta'],
                'fechaNacimiento' => $_POST['fechaNacimiento'],
                'foto' => $_POST['foto'],
            ];
            try {
                BLclients::BLpostInsertClients($datos);
                echo '<script>window.location.href = "Clientes";</script>';
                exit;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    static public function postUpdateClient()
    {
        if (isset($_POST['actualizar'])) {
            $datos = [
                'idCliente' => $_POST['idCliente'],
                'nombreCliente' => $_POST['nombreCliente'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'direccion' => $_POST['direccion'],
                'ciudad' => $_POST['ciudad'],
                'estado' => $_POST['estado'],
                'codigoPostal' => $_POST['codigoPostal'],
                'pais' => $_POST['pais'],
                'informacion' => $_POST['informacion'],
                'tipoCliente' => $_POST['tipoCliente'],
                'preferencia' => $_POST['preferencia'],
                'notas' => $_POST['notas'],
                'fechaUltimaActividad' => $_POST['fechaUltimaActividad'],
                'fechaNacimiento' => $_POST['fechaNacimiento'],
                'foto' => $_POST['foto'],
            ];
            try {
                BLclients::BLpostUpdate($datos);
                echo '<script>window.location.href = "Clientes";</script>';
                exit;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    static public function postInsertContactos()
    {
        if (isset($_POST['registrar'])) {
            $idClienteRedireccion = $_POST['idCliente'];

            $datos = [
                'idCliente' => $idClienteRedireccion,
                'nombre' => $_POST['nombre'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'email' => $_POST['email'],
                'notas' => $_POST['notas'],
            ];
            try {
                BLclients::BLpostInsertContactos($datos);
                echo '<script>window.location.href = "Cliente?idCliente=' . $idClienteRedireccion . '";</script>';
                exit;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
