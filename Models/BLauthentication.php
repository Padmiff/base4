<?php
require_once "Connection.php";

class BLauthentication
{
    /**
     * Intenta autenticar un usuario con el correo electrónico y la contraseña proporcionados.
     * 
     * Este método primero verifica la tabla `empleado`. Si no se encuentra el usuario allí, 
     * busca en las tablas `contactocliente` y `contactoproveedor`.
     * 
     * $email El correo electrónico del usuario.
     * $password La contraseña del usuario.
     * Retorna `true` si la autenticación es exitosa, 
     *                     un mensaje de error si el usuario está bloqueado o 
     *                     `false` si no se encuentra el usuario.
     */
    public static function BLlogin($email, $password)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            // Primero intentar con la tabla empleado
            $sql = 'SELECT e.idEmpleado, e.emailEmpleado, e.hashed_password, r.nombre AS rol_nombre,
                       e.nombreEmpleado, e.apellidoPaterno, e.apellidoMaterno, e.telefono, e.noEmpleado,
                       e.idDepartamento, e.estadoEmpleado
                FROM empleado e
                INNER JOIN rol r ON e.idRol = r.idRol
                WHERE e.emailEmpleado = :email';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['hashed_password'])) {
                if ($user['estadoEmpleado'] === 'Activo') {
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['idEmpleado'];
                    $_SESSION['email'] = $user['emailEmpleado'];
                    $_SESSION['user_rol'] = $user['rol_nombre'];
                    $_SESSION['nombre'] = $user['nombreEmpleado'];
                    $_SESSION['Ape_paterno'] = $user['apellidoPaterno'];
                    $_SESSION['Ape_materno'] = $user['apellidoMaterno'];
                    $_SESSION['telefono'] = $user['telefono'];
                    $_SESSION['N_colaborador'] = $user['noEmpleado'];

                    // Obtener el nombre del departamento desde la base de datos
                    $stmtDepto = $conn->prepare('SELECT nombre FROM departamento WHERE idDepto = :idDepartamento');
                    $stmtDepto->bindParam(':idDepartamento', $user['idDepartamento']);
                    $stmtDepto->execute();
                    $departamento = $stmtDepto->fetchColumn();

                    // Verificar si se encontró el departamento
                    if ($departamento === false) {
                        $_SESSION['departamento'] = 'Desconocido';
                    } else {
                        $_SESSION['departamento'] = $departamento;
                    }

                    // Registrar la actividad
                    self::logActivity($user['idEmpleado'], 'empleado', date('Y-m-d'), date('H:i:s'));

                    return true;
                } else {
                    return "Tu cuenta está bloqueada. Por favor, contacta al administrador.";
                }
            } else {
                // Si no se encuentra en empleado, buscar en contactocliente
                $sql = 'SELECT cc.idContacto, cc.email, cc.hashed_password AS hashed_password, r.nombre
                    FROM contactocliente cc
                    INNER JOIN rol r ON cc.idRol = r.idRol
                    WHERE cc.email = :email';
                $stmt = $conn->prepare($sql);
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['hashed_password'])) {
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['idContacto'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_rol'] = $user['nombre'];

                    // Registrar la actividad
                    self::logActivity($user['idContacto'], 'cliente', date('Y-m-d'), date('H:i:s'));

                    return true;
                }

                // Si no se encuentra en contactocliente, buscar en contactoproveedor
                $sql = 'SELECT cp.idContacto, cp.email, cp.hashed_password AS hashed_password, r.nombre
                    FROM contactoproveedor cp
                    INNER JOIN rol r ON cp.idRol = r.idRol
                    WHERE cp.email = :email';
                $stmt = $conn->prepare($sql);
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['hashed_password'])) {
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['idContacto'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_rol'] = $user['nombre'];

                    // Registrar la actividad
                    self::logActivity($user['idContacto'], 'proveedor', date('Y-m-d'), date('H:i:s'));

                    return true;
                }

                return false; // Si no se encuentra en ninguna tabla, devolver falso
            }
        } catch (PDOException $e) {
            error_log('Error en la consulta de login: ' . $e->getMessage());
            return "Error en el servidor. Por favor, intenta más tarde.";
        }
    }

    /**
     * Registra un nuevo usuario en la base de datos.
     * 
     * Este método primero verifica si el correo electrónico ya está registrado en la base de datos y si es válido.
     * Luego, inserta los datos del usuario en la tabla `empleado`.
     * 
     * $email El correo electrónico del nuevo usuario.
     * $nombre El nombre del nuevo usuario.
     * $password La contraseña del nuevo usuario.
     * Retorna `true` si el registro es exitoso o un mensaje de error en caso contrario.
     */
    public static function BLregisterUser($email, $nombre, $password)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            if (self::emailExistsInDatabase($email)) {
                return "El correo electrónico ya está registrado.";
            }

            if (!self::emailExistsOnWeb($email)) {
                return "El correo electrónico no existe o es inválido.";
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar en la tabla empleado
            $sql = 'INSERT INTO empleado (emailEmpleado, nombreEmpleado, hashed_password) 
                    VALUES (:email, :nombre, :hashed_password)';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email, 'nombre' => $nombre, 'hashed_password' => $hashed_password]);

            return true;
        } catch (PDOException $e) {
            error_log('Error en la consulta de registro: ' . $e->getMessage());
            return "Error en la consulta de registro.";
        }
    }

    /**
     * Verifica si el correo electrónico ya está registrado en la base de datos.
     * 
     * $email El correo electrónico a verificar.
     * Retorna `true` si el correo electrónico ya está registrado, `false` en caso contrario.
     */
    private static function emailExistsInDatabase($email)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            $sql = 'SELECT COUNT(*) AS count FROM empleado WHERE emailEmpleado = :email';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch();

            return ($result['count'] > 0);
        } catch (PDOException $e) {
            error_log('Error en la consulta de verificación de correo electrónico: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si el correo electrónico es válido y tiene registros de DNS MX.
     * 
     * $email El correo electrónico a verificar.
     * Retorna `true` si el correo electrónico es válido y tiene registros de DNS MX, `false` en caso contrario.
     */
    private static function emailExistsOnWeb($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = substr(strrchr($email, "@"), 1);

        $mxhosts = [];
        if (!checkdnsrr($domain, "MX")) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene el rol de un usuario dado su ID.
     * 
     * Este método verifica el rol en las tablas `empleado`, `contactocliente`, y `contactoproveedor`.
     * 
     * $idEmpleado El ID del usuario.
     * El nombre del rol del usuario o `unknown` si no se encuentra.
     */
    public static function obtenerRolUsuario($idEmpleado)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            // Verificar primero en la tabla empleado
            $sql = 'SELECT r.nombreRol 
                    FROM empleado e
                    INNER JOIN rol r ON e.idRol = r.idRol
                    WHERE e.idEmpleado = :idEmpleado';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['idEmpleado' => $idEmpleado]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['nombreRol']; // Devolver el nombre del rol
            }

            // Si no se encuentra en empleado, verificar en contactocliente
            $sql = 'SELECT r.nombreRol 
                    FROM contactocliente cc
                    INNER JOIN rol r ON cc.idRol = r.idRol
                    WHERE cc.idContacto = :idEmpleado';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['idEmpleado' => $idEmpleado]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['nombreRol']; // Devolver el nombre del rol
            }

            // Si no se encuentra en contactocliente, verificar en contactoproveedor
            $sql = 'SELECT r.nombreRol 
                    FROM contactoproveedor cp
                    INNER JOIN rol r ON cp.idRol = r.idRol
                    WHERE cp.idContacto = :idEmpleado';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['idEmpleado' => $idEmpleado]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['nombreRol']; // Devolver el nombre del rol
            }

            return 'unknown'; // Manejar el caso donde no se encuentra el usuario en ninguna tabla
        } catch (PDOException $e) {
            error_log('Error al obtener el rol del usuario: ' . $e->getMessage());
            return 'error'; // Manejar errores de consulta
        }
    }

    /**
     * Registra la actividad de un usuario en la base de datos.
     * 
     * $idContacto El ID del usuario.
     * $tipoUsuario El tipo de usuario ('empleado', 'cliente', 'proveedor').
     * $fecha La fecha de la actividad.
     * $hora La hora de la actividad.
     * Retorna `true` si el registro de actividad es exitoso, `false` en caso contrario.
     */
    private static function logActivity($idContacto, $tipoUsuario, $fecha, $hora)
    {
        try {
            $conn = Connection::connectionBD(); // Obtener la conexión PDO

            // Insertar el registro de actividad
            $sql = 'INSERT INTO RegistroActividad (idContacto, tipoUsuario, fecha, hora) 
                    VALUES (:idContacto, :tipoUsuario, :fecha, :hora)';
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'idContacto' => $idContacto,
                'tipoUsuario' => $tipoUsuario,
                'fecha' => $fecha,
                'hora' => $hora
            ]);

            return true;
        } catch (PDOException $e) {
            error_log('Error al registrar la actividad del usuario: ' . $e->getMessage());
            return false;
        }
    }
}
