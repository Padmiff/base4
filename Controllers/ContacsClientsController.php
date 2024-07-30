<?php
// Importar las clases necesarias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactsClientsController
{
    /**
     * Inserta un nuevo contacto en la base de datos.
     * Los datos del contacto son obtenidos desde un formulario POST.
     */
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
                'estadoContacto' => $_POST['estadoContacto'],
            ];
            try {
                $emailexistente = ContacsClients::BLverificarCorreoExistente($datos['email']);
                if ($emailexistente) {
                    $_SESSION['alert_message'] = 'Error: El correo ya está registrado.';
                    $_SESSION['alert_type'] = 'warning';
                } else {
                    $idContacto = ContacsClients::BLpostInsertContactos($datos);

                    $token = self::generarToken($idContacto);

                    self::enviarCorreo($datos, $datos['email'], $token);

                    $_SESSION['alert_message'] = 'Contacto agregado correctamente.';
                    $_SESSION['alert_type'] = 'success';
                }

                echo '<script>window.location.href = "ContactosClientes?idCliente=' . $idClienteRedireccion . '";</script>';
                exit;
            } catch (Exception $e) {
                $_SESSION['alert_message'] = 'Error al agregar contacto: ' . $e->getMessage();
                $_SESSION['alert_type'] = 'danger';

                echo '<script>window.location.href = "ContactosClientes?idCliente=' . $idClienteRedireccion . '";</script>';
                exit;
            }
        }
    }

    /**
     * Envía un correo electrónico al nuevo contacto.
     *
     *  $datos Los datos del contacto.
     *  $correoDestinatario El correo electrónico del destinatario.
     *  $token El token generado para la asignación de contraseña.
     *
     * Exception Si hay un error al enviar el correo.
     */
    static private function enviarCorreo($datos, $correoDestinatario, $token)
    {
        // Requerir el autoload de Composer para cargar automáticamente las dependencias
        require 'Views/Resources/php/vendor/autoload.php';
        // Configuración de PHPMailer
        $mail = new PHPMailer(true); // Instancia de PHPMailer con excepciones habilitadas

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP(); // Enviar usando SMTP
            $mail->Host       = 'smtp.ionos.mx'; // Servidor SMTP
            $mail->SMTPAuth   = true; // Habilitar autenticación SMTP
            $mail->Username   = 'servicios@correo.base4.mx'; // Usuario SMTP
            $mail->Password   = '0202ChubacaC'; // Contraseña SMTP (debe almacenarse de forma segura)
            $mail->SMTPSecure = 'ssl'; // Habilitar cifrado SSL
            $mail->Port       = 465; // Puerto TCP para conectar

            // Destinatario del correo
            $mail->setFrom('servicios@correo.base4.mx', 'base4');
            $mail->addAddress($correoDestinatario);

            // Formato del correo HTML
            $mail->isHTML(true);
            $mail->Subject = 'Agregar Contacto';

            // Cambiar por la URL del servidor(
            //  <a href='http://localhost/base4_ho/Views/reset_password.php?token=$token'</a>)

            //Contenido del Correo
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
            <p>Estimado <strong>{$datos['nombre']} {$datos['apellidoPaterno']} {$datos['apellidoMaterno']}</strong>,</p>
            <p>Has sido agregado como contacto. Por favor, asigna una contraseña para poder iniciar sesión:</p>
             <center>
               <a href='http://localhost/base4_ho/Views/reset_password.php?token=$token' class='btn'>Establecer contraseña</a>
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
     * Genera un token para la asignación de contraseña.
     *
     *  $idContacto El ID del contacto.
     *
     *  El token generado.
     */
    static private function generarToken($idContacto)
    {
        $token = base64_encode(json_encode(['id' => $idContacto, 'timestamp' => time()]));
        error_log("Generated Token: " . $token); // Log para depuración
        return $token;
    }

    /**
     * Actualiza la contraseña de un contacto utilizando un token.
     * Los datos son obtenidos desde un formulario POST.
     */
    static public function passwordContacto()
    {
        if (isset($_POST['actualizar'])) {
            if (!isset($_POST['token'])) {
                $_SESSION['alert_message'] = 'Token no encontrado en la solicitud.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset_password.php";</script>';
                exit;
            }

            $token = $_POST['token'];
            error_log("Token recibido en la solicitud POST: " . $token);
            $data = json_decode(base64_decode($token), true);

            if (!isset($data) || !isset($data['id']) || !isset($data['timestamp'])) {
                $_SESSION['alert_message'] = 'Token inválido.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset_password.php?token=' . $token . '";</script>';
                exit;
            }

            $idContacto = $data['id'];
            $tokenTimestamp = $data['timestamp'];

            // Verificar si el token ha expirado
            $currentTime = time();
            $tokenExpiryTime = 10800; // 3 horas

            if ($currentTime - $tokenTimestamp > $tokenExpiryTime) {
                $_SESSION['alert_message'] = 'El token ha expirado.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset_password.php?token=' . $token . '";</script>';
                exit;
            }

            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                $_SESSION['alert_message'] = 'Las contraseñas no coinciden.';
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset_password.php?token=' . $token . '";</script>';
                exit;
            }

            try {
                ContacsClients::BLpasswordContacto($password, $idContacto);
                $_SESSION['alert_message'] = 'La contraseña se ha actualizado correctamente.';
                $_SESSION['alert_type'] = 'success';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/login.php";</script>';
                exit;
            } catch (Exception $e) {
                $_SESSION['alert_message'] = 'Error al actualizar la contraseña. Por favor, intente de nuevo.' . $e->getMessage();
                $_SESSION['alert_type'] = 'danger';
                echo '<script>window.location.href = "http://localhost/base4_ho/Views/reset_password.php?token=' . $token . '";</script>';
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
            $idClienteRedireccion = $_POST['idCliente'];
            $datos = [
                'idCliente' => $idClienteRedireccion,
                'idContacto' => $_POST['idContacto'],
                'nombre' => $_POST['nombre'],
                'email' => $_POST['email'],
                'apellidoPaterno' => $_POST['apellidoPaterno'],
                'apellidoMaterno' => $_POST['apellidoMaterno'],
                'notas' => $_POST['notas'],
            ];
            try {
                ContacsClients::BLpostUpdateContact($datos);
                echo '<script>window.location.href = "ContactosClientes?idCliente=' . $idClienteRedireccion . '";</script>';
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
    static public function deleteContact($idContacto, $idCliente)
    {
        try {
            ContacsClients::BLdeleteContacts($idContacto);
            $_SESSION['alert_message'] = 'El contacto ha sido eliminado correctamente.';
            $_SESSION['alert_type'] = 'success';
            echo '<script>window.location.href = "ContactosClientes?idCliente=' . $idCliente . '";</script>';
            exit;
        } catch (Exception $e) {
            $_SESSION['alert_message'] = 'Error al eliminar el contacto: ' . htmlspecialchars($e->getMessage());
            $_SESSION['alert_type'] = 'danger';
        }
    }
}
