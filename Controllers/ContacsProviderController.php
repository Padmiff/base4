<?php
// Importar las clases necesarias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactsProvidersController
{
    /**
     * Método para insertar un nuevo contacto.
     * Este método maneja la solicitud de inserción de un contacto, verifica si el correo ya existe, 
     * genera un token para el contacto y envía un correo de notificación.
     */
    static public function postInsertContacts()
    {
        if (isset($_POST['registrar'])) {
            $idProveedorRedireccion = $_POST['idProveedor'];

            $datos = [
                'idProveedor' => $idProveedorRedireccion,
                'nombreProveedor' => $_POST['nombreProveedor'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'email' => $_POST['email'],
                'notas' => $_POST['notas'],
            ];
            try {
                // Verificar si el correo ya está registrado
                $emailexistente = ContacsProviders::BLverificarCorreoExistente($datos['email']);
                if ($emailexistente) {
                    // Establecer un mensaje de alerta si el correo ya existe
                    $_SESSION['alert_message'] = 'Error: El correo ya está registrado.';
                    $_SESSION['alert_type'] = 'warning';
                } else {
                    // Insertar el nuevo contacto
                    $idContacto = ContacsProviders::BLpostInsertProvider($datos);

                    // Generar un token para el contacto
                    $token = self::generarToken($idContacto);

                    // Enviar un correo de notificación al contacto
                    self::enviarCorreo($datos, $datos['email'], $token);

                    // Establecer un mensaje de alerta si la inserción fue exitosa
                    $_SESSION['alert_message'] = 'Contacto agregado correctamente.';
                    $_SESSION['alert_type'] = 'success';
                }

                echo '<script>window.location.href = "ContactosProveedores?idProveedor=' . $idProveedorRedireccion . '";</script>';
                exit;
            } catch (Exception $e) {
                $_SESSION['alert_message'] = 'Error al agregar contacto: ' . $e->getMessage();
                $_SESSION['alert_type'] = 'danger';

                echo '<script>window.location.href = "ContactosProveedores?idProveedor=' . $idProveedorRedireccion . '";</script>';
                exit;
            }
        }
    }

    /**
     * Método privado para enviar un correo electrónico.
     * Este método configura y envía un correo electrónico utilizando PHPMailer.
     * 
     *  $datos Datos del contacto.
     *  $correoDestinatario Correo electrónico del destinatario.
     *  $token Token generado para el contacto.
     *  Exception Si hay un error al enviar el correo.
     */
    static private function enviarCorreo($datos, $correoDestinatario, $token)
    {
        // Requerir el autoload de Composer para cargar automáticamente las dependencias
        require 'Views/Resources/php/vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
           //Configuracion del servidor SMPT

            $mail->setFrom('servicios@correo.base4.mx', 'base4');
            $mail->addAddress($correoDestinatario);

            $mail->isHTML(true);
            $mail->Subject = 'Registro de contactos';

            // Cambiar por la URL del servidor(
            //  <a href='http://localhost/base4_ho/Views/reset_password.php?token=$token'</a>)

            // Contenido del correo electrónico
            $emailContent = "
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ffffff;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .logo {
            max-width: 150px;
            display: block;
            margin: 0 auto;
            margin-top: 20px;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            margin-top: 20px;
        }
        h1 {
            color: #333333;
            text-align: center;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #666666;
            margin-bottom: 10px;
        }
        strong {
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            border: 1px solid black;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #ffffff;
            color: #ff2929;
            border-color: #ff2929;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <img src='https://www.harineradeoriente.mx/wp-content/uploads/2023/09/1200-X-630.png' class='logo' alt='Logo'>
            <h1>¡Bienvenido a HO!</h1>
        </div>
        <div class='content'>
            <p>Estimado <strong>{$datos['nombreProveedor']} {$datos['apellidoPaterno']} {$datos['apellidoMaterno']}</strong>,</p>
            <p>Has sido agregado como contacto. Por favor, asigna una contraseña para poder iniciar sesión:</p>
             <center>
               <a href='http://localhost/base4_ho/Views/reset-password.php?token=$token' class='btn'>Establecer contraseña</a>
            </center>   
            <p><strong>Aviso de privacidad:</strong> La empresa COMPAÑIA HARINERA DE ORIENTE S.A. DE C.V. utilizará tus datos personales recabados para diversos fines. Para más detalles, consulta nuestro aviso de privacidad.</p>
        </div>
        <div class='footer'>
            <p>Si no has solicitado esto, puedes ignorar este mensaje.</p>
            <p>Este es un mensaje automatizado. Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>
";

            // Cargar el contenido HTML en el correo
            $mail->Body = $emailContent;
            error_log("Email Body: " . $emailContent); ####
            // Enviar el correo
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Manejo de errores
            throw new Exception("Error al enviar el correo: {$e->getMessage()}");
        }
    }

    /**
     * Método privado para generar un token.
     * Este método genera un token codificado en base64 que incluye el ID del contacto y una marca de tiempo.
     * 
     *  $idContacto El ID del contacto.
     *  El token generado.
     */
    static private function generarToken($idContacto)
    {
        $token = base64_encode(json_encode(['id' => $idContacto, 'timestamp' => time()]));
        error_log("Generated Token: " . $token); // Log para depuración
        return $token;
    }

    /**
     * Método para actualizar la contraseña de un contacto.
     * Este método maneja la solicitud de actualización de contraseña, verifica el token y actualiza la contraseña en la base de datos.
     */
    static public function passwordContactoProvider()
    {
        if (isset($_POST['actualizar'])) {
            if (!isset($_POST['token'])) {
                $_SESSION['alert_message'] = 'Token no encontrado en la solicitud.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset-password.php";</script>';
                exit;
            }

            $token = $_POST['token'];
            error_log("Token recibido en la solicitud POST: " . $token);
            $data = json_decode(base64_decode($token), true);

            if (!isset($data) || !isset($data['id']) || !isset($data['timestamp'])) {
                $_SESSION['alert_message'] = 'Token inválido.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset-password.php?token=' . $token . '";</script>';
                exit;
            }

            $idContacto = $data['id'];
            $tokenTimestamp = $data['timestamp'];

            // Verificar si el token ha expirado
            $currentTime = time(); // Obtener el tiempo actual en segundos desde el Unix epoch
            $tokenExpiryTime = 10800; // 3 horas

            if ($currentTime - $tokenTimestamp > $tokenExpiryTime) {
                $_SESSION['alert_message'] = 'El token ha expirado.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset-password.php?token=' . $token . '";</script>';
                exit;
            }

            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                $_SESSION['alert_message'] = 'Las contraseñas no coinciden.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset-password.php?token=' . $token . '";</script>';
                exit;
            }

            try {
                ContacsProviders::BLpasswordContacto($password, $idContacto);
                $_SESSION['alert_message'] = 'La contraseña se ha actualizado correctamente.';
                $_SESSION['alert_type'] = 'success';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/login.php";</script>';
                exit;
            } catch (Exception $e) {
                $_SESSION['alert_message'] = 'Error al actualizar la contraseña. Por favor, intente de nuevo.' . $e->getMessage();
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset-password.php?token=' . $token . '";</script>';
                exit;
            }
        }
    }

    /**
     * Actualiza los datos del  contacto.
     * Los datos son obtenidos desde un formulario POST.
     */
    static public function postUpdateContact()
    {
        if (isset($_POST['actualizar'])) {
            $idProveedorRedireccion = $_POST['idProveedor'];
            $datos = [
                'idProveedor' => $idProveedorRedireccion,
                'idContacto' => $_POST['idContacto'],
                'nombreProveedor' => $_POST['nombreProveedor'],
                'email' => $_POST['email'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'notas' => $_POST['notas'],
            ];
            try {
                ContacsProviders::BLpostUpdateContact($datos);
                echo '<script>window.location.href = "ContactosProveedores?idProveedor=' . $idProveedorRedireccion . '";</script>';
                exit;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    /**
     * "Eliminar" los datos del  contacto.
     * Cambia el estadoContacto a Eliminado (No se borran los datos de la BD).
     */
    static public function deleteContact($idContacto, $idProveedor)
    {
        try {
            ContacsProviders::BLdeleteContacts($idContacto);
            $_SESSION['alert_message'] = 'El contacto ha sido eliminado correctamente.';
            $_SESSION['alert_type'] = 'success';
            echo '<script>window.location.href = "ContactosProveedores?idProveedor=' . $idProveedor . '";</script>';
            exit;
        } catch (Exception $e) {
            $_SESSION['alert_message'] = 'Error al eliminar el contacto: ' . htmlspecialchars($e->getMessage());
            $_SESSION['alert_type'] = 'danger';
        }
    }
}
