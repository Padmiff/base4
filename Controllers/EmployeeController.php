<?php

class EmployeeController
{
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

    static public function getEmployee($noEmpleado)
    {
        try {
            $empleado = BLemployee::getEmployeebyId($noEmpleado);  // Llama al método de la capa de negocios
            return $empleado;  // Retorna los empleados activos obtenidos
        } catch (PDOException $e) {
            echo 'Error al obtener empleados por Numero de empleado: ' . $e->getMessage();
        }
    }

    static public function getEmployeeAll()
    {
        try {
            $empleado = BLemployee::BLgetEmployeeAll();
            return $empleado;
        } catch (PDOException $e) {
            echo 'Error al obtener empleados: ' . $e->getMessage();
        }
    }
}
