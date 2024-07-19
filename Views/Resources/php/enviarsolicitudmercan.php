<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'Connection.php';

try {
    $conn = Connection::connectionBD();
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
    exit;
}

try {
    // Datos del formulario
    $noEmpleado = $_POST['N_colaborador'];
    $idDepartamento = $_POST['departamento'];
    $nombreEmpleado = $_POST['N_empleado'];
    $apellidoPaterno = $_POST['Ape_paterno'];
    $apellidoMaterno = $_POST['Ape_materno'];
    $emailEmpleado = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $folio = $_POST['folio'];
    $Prioridad = $_POST['Prioridad'];
    $gestiona = $_POST['gestiona'];
    $solicitando = $_POST['solicitando'];
    $fecha_pedido = $_POST['fecha_pedido'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $fines_utilizacion = $_POST['fines_utilizacion'];
    $nombre_solicitud = $_POST['nombre_solicitud'];
    $nombre_jefe = $_POST['nombre_jefe'];
    $correo_jefe = $_POST['correo_jefe'];
    $nombre_recibe = $_POST['nombre_recibe'];

    $fecha_pedido = DateTime::createFromFormat('d/m/Y H:i:s', $fecha_pedido)->format('Y-m-d H:i:s');

    // Insertar en la tabla 'soliMercaServi'
    $stmt = $conn->prepare("INSERT INTO soliMercaServi (noEmpleado, idDepartamento, nombreEmpleado, apellidoPaterno, apellidoMaterno, emailEmpleado, telefono, folio, Prioridad, gestiona, fecha_pedido, fecha_entrega, fines_utilizacion, nombre_solicitud, firma_solicitud, nombre_jefe, correo_jefe, nombre_recibe, firma_recibe, solicitando, firma_jefe_recibe) 
    VALUES (:noEmpleado, :idDepartamento, :nombreEmpleado, :apellidoPaterno, :apellidoMaterno, :emailEmpleado, :telefono, :folio, :Prioridad, :gestiona, :fecha_pedido, :fecha_entrega, :fines_utilizacion, :nombre_solicitud, 'Si', :nombre_jefe, :correo_jefe, :nombre_recibe, 'Si', :solicitando, 'Si')");
    $stmt->bindParam(':noEmpleado', $noEmpleado);
    $stmt->bindParam(':idDepartamento', $idDepartamento);
    $stmt->bindParam(':nombreEmpleado', $nombreEmpleado);
    $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
    $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
    $stmt->bindParam(':emailEmpleado', $emailEmpleado);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':folio', $folio);
    $stmt->bindParam(':Prioridad', $Prioridad);
    $stmt->bindParam(':gestiona', $gestiona);
    $stmt->bindParam(':solicitando', $solicitando);
    $stmt->bindParam(':fecha_pedido', $fecha_pedido);
    $stmt->bindParam(':fecha_entrega', $fecha_entrega);
    $stmt->bindParam(':fines_utilizacion', $fines_utilizacion);
    $stmt->bindParam(':nombre_solicitud', $nombre_solicitud);
    $stmt->bindParam(':nombre_jefe', $nombre_jefe);
    $stmt->bindParam(':correo_jefe', $correo_jefe);
    $stmt->bindParam(':nombre_recibe', $nombre_recibe);
    $stmt->execute();

    // Obtener el ID de la última inserción
    $soliMercaServi_id = $conn->lastInsertId();

    // Insertar en la tabla 'mercancias_servicios'
    foreach ($_POST['partida'] as $index => $partida) {
        $cantidad = $_POST['cantidad'][$index];
        $unidad = $_POST['unidad'][$index];
        $descripcion = $_POST['descripcion'][$index];

        $stmt = $conn->prepare("INSERT INTO mercancias_servicios (soliMercaServi_id, partida, cantidad, unidad, descripcion) VALUES (:soliMercaServi_id, :partida, :cantidad, :unidad, :descripcion)");
        $stmt->bindParam(':soliMercaServi_id', $soliMercaServi_id);
        $stmt->bindParam(':partida', $partida);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':unidad', $unidad);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
    }

    echo "Datos insertados correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;


// Importar las clases de PHPMailer y Dompdf al espacio de nombres global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

// Cargar el autoloader de Composer
require 'vendor/autoload.php';

// Recoger datos del formulario
$N_colaborador = $_POST['N_colaborador'] ?? '';
$departamento = $_POST['departamento'] ?? '';
$N_empleado = $_POST['N_empleado'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$Folio = $_POST['folio'] ?? '';
$Prioridad = $_POST['Prioridad'] ?? '';
$gestiona = $_POST['gestiona'] ?? '';
$solicitando = $_POST['solicitando'] ?? '';
$fecha_pedido = $_POST['fecha_pedido'] ?? '';
$fecha_entrega = $_POST['fecha_entrega'] ?? '';
$fines_utilizacion = $_POST['fines_utilizacion'] ?? '';
$nombre_solicitud = $_POST['nombre_solicitud'] ?? '';
$firma_solicitud = $_POST['firma_solicitud'] ?? '';
$nombre_jefe = $_POST['nombre_jefe'] ?? '';
$correo_jefe = $_POST['correo_jefe'] ?? '';
$firma_jefe_recibe = $_POST['firma_jefe_recibe'] ?? '';
$nombre_recibe = $_POST['nombre_recibe'] ?? '';
$firma_recibe = $_POST['firma_recibe'] ?? '';

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
    $mail->addAddress($correo, $N_colaborador); // Agregar destinatario
    $mail->addAddress($correo_jefe, $N_colaborador);

    // Formato del correo HTML
    $mail->isHTML(true); // Formato HTML
    $mail->Subject = 'Solicitud de mercancia o servicio';

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
                <p><strong>Número de colaborador:</strong> $N_colaborador</p>
                <p><strong>Departamento:</strong> $departamento</p>
                <p><strong>Nombre de colaborador:</strong> $N_empleado</p>
                <p><strong>Correo electrónico:</strong> $correo</p>
                <p><strong>Número de teléfono:</strong> $telefono</p>
                <hr>
                <p><strong>Folio:</strong> $Folio</p>
                <p><strong>Prioridad de atención:</strong> $Prioridad</p>
                <p><strong>Departamento que gestionará la compra:</strong> $gestiona</p>
                <p><strong>Solicitando:</strong> $solicitando</p>
                <p><strong>Fecha de pedido:</strong> $fecha_pedido</p>
                <p><strong>Fecha de entrega:</strong> $fecha_entrega</p>
                <hr>
                <p><strong>Mercancías y/o Servicios:</strong></p>
                <table>
                    <thead>
                        <tr>
                            <th>Partida</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>";

    if (!empty($_POST['partida'])) {
        foreach ($_POST['partida'] as $index => $partida) {
            $cantidad = $_POST['cantidad'][$index] ?? '';
            $unidad = $_POST['unidad'][$index] ?? '';
            $descripcion = $_POST['descripcion'][$index] ?? '';

            $emailContent .= "
                            <tr>
                                <td>$partida</td>
                                <td>$cantidad</td>
                                <td>$unidad</td>
                                <td>$descripcion</td>
                            </tr>";
        }
    }

    $emailContent .= "
                    </tbody>
                </table>
                <hr>
                <p><strong>Fines de utilización:</strong><br>$fines_utilizacion</p>
                <hr>
                <p><strong>Nombre de la persona que hace la solicitud:</strong> $nombre_solicitud</p>
                <p><strong>Firma de la persona que hace la solicitud:</strong><br>$firma_solicitud</p>
                <hr>
                <p><strong>Nombre del Jefe inmediato:</strong> $nombre_jefe</p>
                <p><strong>Correo electrónico del Jefe inmediato:</strong> $correo_jefe</p>
                <p><strong>Firma del Jefe inmediato de la persona que hace la solicitud:</strong> $firma_jefe_recibe</p>
                <hr>
                <p><strong>Nombre de quién recibe la solicitud en el Departamento de compras:</strong> $nombre_recibe</p>
                <p><strong>Firma de quién recibe la solicitud en el Departamento de compras:</strong><br>$firma_recibe</p>
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
    $mail->addStringAttachment($pdfOutput, 'solicitud de mercancia.pdf');

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
