<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Recoger datos del formulario
$N_empleado = $_POST['N_empleado'] ?? '';
$departamento = $_POST['departamento'] ?? '';
$Nom_colab = $_POST['Nom_colab'] ?? '';
$apellido_paterno = $_POST['apellido_paterno'] ?? '';
$apellido_materno = $_POST['apellido_materno'] ?? '';
$correo = $_POST['correo'] ?? '';
$numero = $_POST['numero'] ?? '';
$aviso = $_POST['aviso'] ?? '';
$extraEmails = json_decode($_POST['extraEmails'] ?? '[]', true);

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Error: La dirección de correo electrónico '$correo' no es válida");
}

// Crear una instancia de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Contenido HTML para el PDF
$html = '
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #007bff;
            color: #fff;
            padding: 10px 0;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .header img {
            max-width: 150px;
            display: block;
            margin: 0 auto;
        }
        .header h1 {
            margin: 10px 0 0;
            font-size: 24px;
        }
        .details {
            margin: 20px 0;
        }
        .details p {
            font-size: 16px;
            margin: 10px 0;
            color: #555;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding: 10px 0;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
              <img src="formularios/img/logo.png" alt="Logo">
            <h1>Detalles del Colaborador</h1>
        </div>
        <div class="details">
            <p><strong>Número de colaborador:</strong> ' . $N_empleado . '</p>
            <p><strong>Departamento:</strong> ' . $departamento . '</p>
            <p><strong>Nombre del colaborador:</strong> ' . $Nom_colab . '</p>
            <p><strong>Apellido paterno:</strong> ' . $apellido_paterno . '</p>
            <p><strong>Apellido materno:</strong> ' . $apellido_materno . '</p>
            <p><strong>Correo electrónico:</strong> ' . $correo . '</p>
            <p><strong>Número de contacto:</strong> ' . $numero . '</p>
            <p><strong>Aviso de privacidad:</strong> ' . $aviso . '</p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automatizado. Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>';

// Cargar el contenido HTML en Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Obtener la salida del PDF como un string
$pdfOutput = $dompdf->output();

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host       = 'smtp.ionos.mx';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'servicios@correo.base4.mx';
    $mail->Password   = '0202ChubacaC';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Destinatarios
    $mail->setFrom('servicios@correo.base4.mx', 'base4');
    $mail->addAddress($correo, $Nom_colab);

    foreach ($extraEmails as $extraEmail) {
        if (filter_var($extraEmail, FILTER_VALIDATE_EMAIL)) {
            $mail->addAddress($extraEmail);
        }
    }

    // Adjuntar el PDF
    $mail->addStringAttachment($pdfOutput, 'Detalles_Colaborador.pdf');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Detalles del Colaborador';

    // Construir el cuerpo del mensaje HTML personalizado con estilos CSS
    $logoUrl = 'formularios/img/logo.png'; // Ruta local al logo
    $message = '
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
                background-color: #007bff;
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
            .footer {
                background-color: #f8f9fa;
                padding: 10px;
                text-align: center;
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
            }
            h1 {
                color: #333333;
                text-align: center;
            }
            p {
                font-size: 16px;
                color: #666666;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="' . $logoUrl . '" class="logo" alt="Logo">
                <h1>Detalles del Colaborador</h1>
            </div>
            <p><strong>Número de colaborador:</strong> ' . $N_empleado . '</p>
            <p><strong>Departamento:</strong> ' . $departamento . '</p>
            <p><strong>Nombre del colaborador:</strong> ' . $Nom_colab . '</p>
            <p><strong>Apellido paterno:</strong> ' . $apellido_paterno . '</p>
            <p><strong>Apellido materno:</strong> ' . $apellido_materno . '</p>
            <p><strong>Correo electrónico:</strong> ' . $correo . '</p>
            <p><strong>Número de contacto:</strong> ' . $numero . '</p>
            <p><strong>Aviso de privacidad:</strong>' . $aviso . '</p>
            <div class="footer">
                <p>Este es un mensaje automatizado. Por favor, no respondas a este correo.</p>
            </div>
        </div>
    </body>
    </html>';

    $mail->Body = $message;

    // Enviar el correo
    $mail->send();
    echo 'Mensaje enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
?>
