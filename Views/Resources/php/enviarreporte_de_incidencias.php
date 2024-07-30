<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

require 'Connection.php';
require 'vendor/autoload.php';


try {

    // Ejemplo de cadena que contiene detalles del archivo
    $evidenciaString = "Name = Captura de pantalla 2024-02-18 141055.png, Size = 49160 bytes, Type = image/png";

    // Extraer solo el nombre del archivo usando una expresión regular
    preg_match('/Name\s*=\s*([^,]+)/', $evidenciaString, $matches);
    $nombreArchivo = trim($matches[1]);

    // Mostrar el nombre del archivo extraído para depuración
    echo '<pre>';
    echo "Nombre del archivo extraído: $nombreArchivo\n";
    echo '</pre>';


    $conn = Connection::connectionBD();

    // Datos del formulario con valores predeterminados para campos vacíos
    $noEmpleado = $_POST['N_colaborador'] ?? '';
    $nombreDepartamento = $_POST['departamento'] ?? '';
    $nombreEmpleado = $_POST['N_empleado'] ?? '';
    $apellidoPaterno = $_POST['Ape_paterno'] ?? '';
    $apellidoMaterno = $_POST['Ape_materno'] ?? '';
    $emailEmpleado = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $folio = $_POST['folio'] ?? '';
    $fecha_reporte = $_POST['fecha_reporte'] ?? '';
    $tipo_inc = $_POST['tipo_inc'] ?? '';
    $dep_rep = $_POST['dep_rep'] ?? '';
    $descrip_inc = $_POST['descrip_inc'] ?? '';
    $evidencia = $_POST['evidencia'] ?? '';
    $fecha_atencion = $_POST['fecha_atencion'] ?? '';
    $firma_de_conformidad = $_POST['firma_de_conformidad'] ?? '';

    // Obtener idDepto a partir del nombre del departamento
    $stmtDepto = $conn->prepare("SELECT idDepto FROM departamento WHERE nombre = :nombreDepartamento");
    $stmtDepto->bindParam(':nombreDepartamento', $nombreDepartamento);
    $stmtDepto->execute();
    $idDepartamento = $stmtDepto->fetchColumn();

    if (!$idDepartamento) {
        throw new Exception('Departamento no encontrado.');
    }

    // Obtener nombres de los archivos subidos
    $evidenciaFiles = [];
    if (!empty($_FILES['evidenceFiles']['name'][0])) {
        foreach ($_FILES['evidenceFiles']['name'] as $key => $fileName) {
            // Agregar nombre de archivo a la lista
            $evidenciaFiles[] = $fileName;
        }
    }

    // Convertir los nombres de los archivos en una cadena separada por comas
    $evidencia = implode(',', $evidenciaFiles);

    // Consulta de inserción
    $query = "INSERT INTO reinci (
        noEmpleado,
        idDepartamento,
        nombreEmpleado,
        apellidoPaterno,
        apellidoMaterno,
        emailEmpleado,
        telefono,
        folio,
        fecha_reporte,
        tipo_inc,
        dep_rep,
        descrip_inc,
        evidencia,
        fecha_atencion,
        firma_de_conformidad
    ) VALUES (
        :noEmpleado,
        :idDepartamento,
        :nombreEmpleado,
        :apellidoPaterno,
        :apellidoMaterno,
        :emailEmpleado,
        :telefono,
        :folio,
        :fecha_reporte,
        :tipo_inc,
        :dep_rep,
        :descrip_inc,
        :evidencia,
        :fecha_atencion,
        :firma_de_conformidad
    )";

    // Preparar la consulta
    $stmt = $conn->prepare($query);

    // Vincular parámetros
    $stmt->bindParam(':noEmpleado', $noEmpleado);
    $stmt->bindParam(':idDepartamento', $idDepartamento);
    $stmt->bindParam(':nombreEmpleado', $nombreEmpleado);
    $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
    $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
    $stmt->bindParam(':emailEmpleado', $emailEmpleado);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':folio', $folio);
    $stmt->bindParam(':fecha_reporte', $fecha_reporte);
    $stmt->bindParam(':tipo_inc', $tipo_inc);
    $stmt->bindParam(':dep_rep', $dep_rep);
    $stmt->bindParam(':descrip_inc', $descrip_inc);
    $stmt->bindParam(':evidencia', $evidencia);
    $stmt->bindParam(':fecha_atencion', $fecha_atencion);
    $stmt->bindParam(':firma_de_conformidad', $firma_de_conformidad);

    $stmt->execute();

    $response['message'] = 'Datos insertados correctamente.';
} catch (PDOException $e) {
    $response['error'] = 'Error al insertar datos: ' . $e->getMessage();
} catch (Exception $e) {
    $response['error'] = 'Error al procesar la solicitud: ' . $e->getMessage();
}

// Recoger datos del formulario
$N_colaborador = $_POST['N_colaborador'] ?? '';
$departamento = $_POST['departamento'] ?? '';
$N_empleado = $_POST['N_empleado'] ?? '';
$Ape_paterno = $_POST['Ape_paterno'] ?? '';
$Ape_materno = $_POST['Ape_materno'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$Folio = $_POST['folio'] ?? '';
$fecha_reporte = $_POST['fecha_reporte'] ?? '';
$tipo_inc = $_POST['tipo_inc'] ?? '';
$dep_rep = $_POST['dep_rep'] ?? '';
$descrip_inc = $_POST['descrip_inc'] ?? '';
$evidencia = $_POST['evidencia'] ?? '';
$fecha_atencion = $_POST['fecha_atencion'] ?? '';
$firma_de_conformidad = $_POST['firma_de_conformidad'] ?? '';

// Crear una instancia de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Crear una instancia de PHPMailer; pasando `true` habilita las excepciones
$mail = new PHPMailer(true);

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
            background-color: FFFFFF;
            color: #000;
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
              <img src="../img/logo.png" alt="Harina de Oriente">
            <h1>Reporte de incidencia informatica</h1>
        </div>
        <div class="details">
            <p><strong>Número de colaborador:</strong> ' . $N_colaborador . '</p>
            <p><strong>Departamento:</strong> ' . $departamento . '</p>
            <p><strong>Nombre del colaborador:</strong> ' . $N_empleado . '</p>
            <p><strong>Apellido paterno:</strong> ' . $Ape_paterno  . '</p>
            <p><strong>Apellido materno:</strong> ' . $Ape_materno  . '</p>
            <p><strong>Correo electrónico:</strong> ' . $correo . '</p>
            <p><strong>Número de contacto:</strong> ' . $telefono . '</p>
            <p><strong>Folio:</strong> ' . $Folio . '</p>
            <p><strong>Fecha de reporte:</strong> ' . $fecha_reporte . '</p>
            <p><strong>¿Qué tipo de incidencia reporta?:</strong> ' . $tipo_inc . '</p>
            <p><strong>¿En qué departamento o área de la organización se tiene esta incidencia?:</strong> ' . $dep_rep . '</p>
            <p><strong>Descripción de la incidencia:</strong> ' . $descrip_inc . '</p>
            <p><strong>Evidencia:</strong> ' . $evidencia . '</p>
            <p><strong>Fecha de atención:</strong> ' . $fecha_atencion . '</p>
            <p><strong>Firma de conformidad:</strong> ' . $firma_de_conformidad . '</p>
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
    $mail->addAddress($correo, $N_empleado);

    // Adjuntar el PDF
    $mail->addStringAttachment($pdfOutput, 'Reporte de incidencias informaticas.pdf');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Reporte de incidencia informatica';

    // Archivos adjuntos
    if (!empty($_FILES['evidenceFiles'])) {
        foreach ($_FILES['evidenceFiles']['tmp_name'] as $key => $tmp_name) {
            $mail->addAttachment($tmp_name, $_FILES['evidenceFiles']['name'][$key]);
        }
    }


    // Construir el cuerpo del mensaje HTML personalizado con estilos CSS
    $logoUrl = 'https://www.harineradeoriente.mx/wp-content/uploads/2023/09/1200-X-630.png'; // Ruta local al logo
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
                background-color: #ffffff;
                color: #000000;
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
                <h1>Reporte de incidencia informatica</h1>
            </div>
            <p><strong>Número de colaborador:</strong> ' . $N_colaborador . '</p>
            <p><strong>Departamento:</strong> ' . $departamento . '</p>
            <p><strong>Nombre del colaborador:</strong> ' . $N_empleado . '</p>
            <p><strong>Apellido paterno:</strong> ' . $Ape_paterno  . '</p>
            <p><strong>Apellido materno:</strong> ' . $Ape_materno  . '</p>
            <p><strong>Correo electrónico:</strong> ' . $correo . '</p>
            <p><strong>Número de contacto:</strong> ' . $telefono . '</p>
            <p><strong>Folio:</strong> ' . $Folio . '</p>
            <p><strong>Fecha de reporte:</strong> ' . $fecha_reporte . '</p>
            <p><strong>¿Qué tipo de incidencia reporta?:</strong> ' . $tipo_inc . '</p>
            <p><strong>¿En qué departamento o área de la organización se tiene esta incidencia?:</strong> ' . $dep_rep . '</p>
            <p><strong>Descripción de la incidencia:</strong> ' . $descrip_inc . '</p>
            <p><strong>Evidencia:</strong> ' . $evidencia . '</p>
            <p><strong>Fecha de atención:</strong> ' . $fecha_atencion . '</p>
            <p><strong>Firma de conformidad:</strong> ' . $firma_de_conformidad . '</p>
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
