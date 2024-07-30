<?php

/**
 * 
 * Controlador para gestionar las operaciones relacionadas con los empleados en el sistema.
 * Utiliza la capa de negocio BLemployee para realizar operaciones CRUD y otras acciones sobre los empleados.
 */
class EmployeeController
{
    /**
     * Obtiene la lista de empleados activos.
     * 
     * Lista de empleados activos.
     */
    static public function getUsersActive()
    {
        try {
            $empleadosActivos = BLemployee::getEmployeeActive();  // Llama al método de la capa de negocios

            return $empleadosActivos;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            // Manejo de errores
            echo 'Error al obtener empleados activos: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene la lista de empleados inactivos.
     * 
     * Lista de empleados inactivos.
     */
    static public function getUsersInactive()
    {
        try {
            $empleadosInactivos = BLemployee::getEmployeeInactive();  // Llama al método de la capa de negocios

            return $empleadosInactivos;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            // Manejo de errores
            echo 'Error al obtener empleados Bloqueados: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene la información de un empleado específico por su número de empleado.
     * 
     * $noEmpleado Número del empleado a obtener.
     * Información del empleado.
     */
    static public function getEmployee($noEmpleado)
    {
        try {
            $empleado = BLemployee::getEmployeebyId($noEmpleado);  // Llama al método de la capa de negocios
            return $empleado;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            echo 'Error al obtener empleados por Numero de empleado: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene la lista de todos los empleados.
     * 
     * Lista de todos los empleados.
     */
    static public function getEmployeeAll()
    {
        try {
            $empleado = BLemployee::BLgetEmployeeAll();
            return $empleado;
        } catch (PDOException $e) {
            echo 'Error al obtener empleados: ' . $e->getMessage();
        }
    }

    /**
     * Bloquea (desactiva) un empleado específico.
     * 
     * $idEmpleado ID del empleado a bloquear.
     */
    static public function blockEmployee($idEmpleado)
    {
        try {
            BLemployee::BLblockemployee($idEmpleado);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al bloquear empleado: ' . $e->getMessage();
        }
    }

    /**
     * Desbloquea (Activa) un empleado específico.
     * 
     * $idEmpleado ID del empleado a desbloquear.
     */
    static public function unlockEmployee($idEmpleado)
    {
        try {
            BLemployee::BLunlockemployee($idEmpleado);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al bloquear empleado: ' . $e->getMessage();
        }
    }

    /**
     * Elimina un empleado específico del sistema.
     * 
     * $idEmpleado ID del empleado a eliminar (No elimina el empleado de la BD).
     */
    static public function deleteEmployee($idEmpleado)
    {
        try {
            BLemployee::BLdeleteEmployee($idEmpleado);
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al bloquear empleado: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene la lista de roles disponibles para los empleados.
     * 
     * Lista de roles.
     */
    static public function getRoles()
    {
        try {
            $roles = BLemployee::BLgetRoles();
            return $roles;
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al obtener roles: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene la lista de departamentos disponibles.
     * 
     * Lista de departamentos.
     */
    static public function getdepartamento()
    {
        try {
            $departamento = BLemployee::BLgetdepartamento();
            return $departamento;
        } catch (Exception $e) {
            // Manejo de errores: Puedes redirigir a una página de error o mostrar un mensaje
            echo 'Error al obtener departamentos: ' . $e->getMessage();
        }
    }

    /**
     * Inserta un nuevo empleado en el sistema.
     * 
     * Redirige a la vista "Empleados" en caso de éxito.
     */
    static public function postInsertColaboradores()
    {
        if (isset($_POST['registrar'])) {
            $datos = [
                'nombreEmpleado' => $_POST['nombreEmpleado'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'fechaNacimiento' => $_POST['fechaNacimiento'],
                'genero' => $_POST['genero'],
                'emailEmpleado' => $_POST['emailEmpleado'],
                'hashed_password' => $_POST['hashed_password'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'ciudad' => $_POST['ciudad'],
                'estado' => $_POST['estado'],
                'codigoPostal' => $_POST['codigoPostal'],
                'pais' => $_POST['pais'],
                'fechaContratacion' => $_POST['fechaContratacion'],
                'salario' => $_POST['salario'],
                'noEmpleado' => $_POST['noEmpleado'],
                'estadoEmpleado' => $_POST['estadoEmpleado'],
                'notas' => $_POST['notas'],
                'numeroSeguroSocial' => $_POST['numeroSeguroSocial'],
                'rfc' => $_POST['rfc'],
                'idDepartamento' => $_POST['idDepartamento'],
                'idRol' => $_POST['idRol'],
            ];
            try {
                // Llamar a la función del modelo para insertar en la base de datos
                BLemployee::BLpostInsert($datos);
                // Redirigir o mostrar un mensaje de éxito
                echo '<script>window.location.href = "Empleados";</script>';
                exit;
            } catch (Exception $e) {
                // Manejar el error, mostrar un mensaje al usuario, registrar el error, etc.
                echo "Error: " . $e->getMessage();
            }
        }
    }

    /**
     * Actualiza la información de un empleado existente.
     * 
     * Redirige a la vista "Empleados" en caso de éxito.
     */
    static public function postUpdateColaborador()
    {
        if (isset($_POST['actualizar'])) {
            $datos = [
                'idEmpleado' => $_POST['idEmpleado'],
                'nombreEmpleado' => $_POST['nombreEmpleado'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'fechaNacimiento' => $_POST['fechaNacimiento'],
                'genero' => $_POST['genero'],
                'emailEmpleado' => $_POST['emailEmpleado'],
                'hashed_password' => $_POST['hashed_password'],
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'ciudad' => $_POST['ciudad'],
                'estado' => $_POST['estado'],
                'codigoPostal' => $_POST['codigoPostal'],
                'pais' => $_POST['pais'],
                'fechaContratacion' => $_POST['fechaContratacion'],
                'salario' => $_POST['salario'],
                'noEmpleado' => $_POST['noEmpleado'],
                'notas' => $_POST['notas'],
                'numeroSeguroSocial' => $_POST['numeroSeguroSocial'],
                'rfc' => $_POST['rfc'],
                'idDepartamento' => $_POST['idDepartamento'],
                'idRol' => $_POST['idRol'],
            ];
            try {
                // Llamar a la función del modelo para actualizar los datos
                BLemployee::BLpostUpdate($datos);
                // Redirigir o mostrar un mensaje de éxito
                echo '<script>window.location.href = "Empleados";</script>';
                exit;
            } catch (Exception $e) {
                // Manejar el error, mostrar un mensaje al usuario, registrar el error, etc.
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
