<?php
// Importar las clases de PHPMailer y Dompdf al espacio de nombres global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

// Cargar el autoloader de Composer
require 'vendor/autoload.php';

// Recoger datos del formulario
$fecha_de_solicitud = $_POST['fecha_de_solicitud'] ?? '';
$nombre_empresa = $_POST['nombre_empresa'] ?? '';
$nombre_visitante = $_POST['nombre_visitante'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$folio = $_POST['folio'] ?? '';
$prioridad = $_POST['prioridad'] ?? '';
$entrada_o_salida = $_POST['entrada_o_salida'] ?? '';
$fecha_devolucion = $_POST['fecha_devolucion'] ?? '';
$fines_utilizacion = $_POST['fines_utilizacion'] ?? '';
$dynamicTable = $_POST['dynamicTable'] ?? '';
$nombre_responsable = $_POST['nombre_responsable'] ?? '';
$firma_de_responsable = $_POST['firma_de_responsable'] ?? '';
$responsabilidad = $_POST['responsabilidad'] ?? '';
$nombre_aut_ho = $_POST['nombre_aut_ho'] ?? '';
$correo_aut_ho = $_POST['correo_aut_ho'] ?? '';
$firma_aut_ho = $_POST['firma_aut_ho'] ?? '';

// Manejar la carga del archivo de evidencia
if (isset($_FILES['evidencia']) && $_FILES['evidencia']['error'] == UPLOAD_ERR_OK) {
    $evidenciaTempPath = $_FILES['evidencia']['tmp_name'];
    $evidenciaFileName = $_FILES['evidencia']['name'];
    $evidenciaFileType = $_FILES['evidencia']['type'];
}

// Crear una instancia de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Crear una instancia de PHPMailer; pasando `true` habilita las excepciones
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP(); // Enviar usando SMTP
    $mail->Host       = 'smtp.ionos.mx'; // Servidor SMTP
    $mail->SMTPAuth   = true; // Habilitar autenticación SMTP
    $mail->Username   = 'servicios@correo.base4.mx'; // Usuario SMTP
    $mail->Password   = '0202ChubacaC'; // Contraseña SMTP (debe almacenarse de forma segura)
    $mail->SMTPSecure = 'ssl'; // Habilitar cifrado TLS implícito
    $mail->Port       = 465; // Puerto TCP para conectar

    // Destinatario del correo
    $mail->setFrom('servicios@correo.base4.mx', 'base4');
    $mail->addAddress($correo, $nombre_empresa); // Agregar destinatario

    // Formato del correo HTML
    $mail->isHTML(true); // Formato HTML
    $mail->Subject = 'Entrada/salida de equipo de computo (visitantes)';

    // Estilos CSS para el correo electrónico
    $emailStyles = "
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                background-color: #f4f4f4;
                color: #333;
            }
            .container {
                width: 80%;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            h2 {
                color: #2A7BE4;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            table, th, td {
                border: 1px solid #ccc;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            hr {
                border: 1px solid #ccc;
                margin-top: 20px;
                margin-bottom: 20px;
            }
        </style>
    ";

    // Construir el contenido del correo en HTML con estilos CSS
    $emailContent = "
        <html>
        <head>
            <title>Solicitud de mercancía o servicio</title>
            $emailStyles
        </head>
        <body>
            <div class='container'>
                <h2>Detalles del Formulario de Solicitud</h2>
                <p><strong>Fecha de Solicitud:</strong> $fecha_de_solicitud</p>
                <p><strong>Nombre de la Empresa:</strong> $nombre_empresa</p>
                <p><strong>Nombre del Visitante:</strong> $nombre_visitante</p>
                <p><strong>Correo Electrónico:</strong> $correo</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <hr>
                <p><strong>Folio:</strong> $folio</p>
                <p><strong>Prioridad:</strong> $prioridad</p>
                <p><strong>Entrada o Salida:</strong> $entrada_o_salida</p>
                <p><strong>Fecha de Devolución:</strong> $fecha_devolucion</p>
                <hr>
                <p><strong>Motivo:</strong><br>$fines_utilizacion</p>
                <hr>
                <p><strong>Nombre del Responsable:</strong> $nombre_responsable</p>
                <p><strong>Firma del Responsable:</strong><br>$firma_de_responsable</p>
                <hr>
                <p><strong>Responsabilidad:</strong><br>$responsabilidad</p>
                <hr>
                <p><strong>Nombre del Autorizador HO:</strong> $nombre_aut_ho</p>
                <p><strong>Correo del Autorizador HO:</strong> $correo_aut_ho</p>
                <p><strong>Firma del Autorizador HO:</strong><br>$firma_aut_ho</p>
                <hr>
                <p><strong>Caracteristicas:</strong></p>
                <table>
                    <thead>
                        <tr>
                            <th>Tipo de equipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Numero de serie</th>
                            <th>¿Este equipo pertenece a HO?</th>
                        </tr>
                    </thead>
                    <tbody>";

    if (!empty($_POST['tipo_equipo'])) {
        foreach ($_POST['tipo_equipo'] as $index => $tipo_equipo) {
            $marca = $_POST['marca'][$index] ?? '';
            $modelo = $_POST['modelo'][$index] ?? '';
            $numero_serie = $_POST['numero_serie'][$index] ?? '';
            $pertenece_a_ho = $_POST['pertenece_a_ho'][$index] ?? '';

            $emailContent .= "
                            <tr>
                                <td>$tipo_equipo</td>
                                <td>$marca</td>
                                <td>$modelo</td>
                                <td>$numero_serie</td>
                                <td>$pertenece_a_ho</td>
                            </tr>";
        }
    }

    $emailContent .= "
                    </tbody>
                </table>
            </div>
        </body>
        </html>
    ";

    // Cargar el contenido HTML en Dompdf
    $dompdf->loadHtml($emailContent);

    // Renderizar el PDF
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Obtener la salida del PDF como un string
    $pdfOutput = $dompdf->output();

    // Adjuntar el PDF al correo
    $mail->addStringAttachment($pdfOutput, 'Entrada/salida de equipo de computo (visitante).pdf');

    // Añadir el contenido al cuerpo del correo
    $mail->Body = $emailContent;

    // Enviar el correo
    $mail->send();

    // Mensaje de éxito
    echo json_encode(['message' => 'Correo enviado correctamente']); // Respuesta JSON

} catch (Exception $e) {
    // Manejo de errores
    echo json_encode(['error' => "Error al enviar el correo: {$mail->ErrorInfo}"]); // Respuesta JSON
}
