<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Importar las clases de PHPMailer y Dompdf al espacio de nombres global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

require 'vendor/autoload.php';
require 'Connection.php';

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

    // Recoger datos del formulario
    $N_colaborador = $_POST['N_colaborador'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $N_empleado = $_POST['N_empleado'] ?? '';
    $Ape_paterno = $_POST['Ape_paterno'] ?? '';
    $Ape_materno = $_POST['Ape_materno'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $folio = $_POST['folio'] ?? '';
    $fecha_solicitud = $_POST['fecha_solicitud'] ?? '';
    $prioridad = $_POST['prioridad'] ?? '';
    $entrada_o_salida = $_POST['entrada_o_salida'] ?? '';
    $fecha_devolucion = $_POST['fecha_devolucion'] ?? '';
    $fines_utilizacion = $_POST['fines_utilizacion'] ?? '';
    $nombre_colab = $_POST['nombre_colab'] ?? '';
    $firma_de_responsable = $_POST['firma_de_responsable'] ?? 'Si';
    $nombre_aut_ho = $_POST['nombre_aut_ho'] ?? '';
    $correo_aut_ho = $_POST['correo_aut_ho'] ?? '';
    $firma_aut_ho = $_POST['firma_aut_ho'] ?? 'Si';
    $evidencia = $_POST['evidencia'] ?? '';

    // Obtener idDepto a partir del nombre del departamento
    $nombreDepartamento = $_POST['departamento'] ?? ''; // Asegúrate de que este campo esté presente en el formulario
    $stmtDepto = $conn->prepare("SELECT idDepto FROM departamento WHERE nombre = :departamento");
    $stmtDepto->bindParam(':departamento', $departamento);
    $stmtDepto->execute();
    $idDepartamento = $stmtDepto->fetchColumn();

    if (!$idDepartamento) {
        throw new Exception("Departamento '$departamento' no encontrado.");
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
    $query = "INSERT INTO ESColaboradores (
        noEmpleado,
        idDepartamento,
        nombreEmpleado,
        apellidoPaterno,
        apellidoMaterno,
        emailEmpleado,
        telefono,
        folio,
        fecha_solicitud,
        propietario_equipo,
        entrada_salida,
        fecha_devolucion,
        motivo,
        evidencia,
        nombre_colaborador,
        aceptacion_responsabilidad,
        nombre_autorizador,
        correo_autorizador,
        firma_autorizador
    ) VALUES (
        :N_colaborador,
        :idDepartamento,
        :N_empleado,
        :Ape_paterno,
        :Ape_materno,
        :correo,
        :telefono,
        :folio,
        :fecha_solicitud,
        :prioridad,
        :entrada_o_salida,
        :fecha_devolucion,
        :fines_utilizacion,
        :evidencia,
        :nombre_colab,
        :firma_de_responsable,
        :nombre_aut_ho,
        :correo_aut_ho,
        :firma_aut_ho
    )";

    // Preparar la consulta
    $stmt = $conn->prepare($query);

    // Vincular parámetros
    $stmt->bindParam(':N_colaborador', $N_colaborador);
    $stmt->bindParam(':idDepartamento', $idDepartamento);
    $stmt->bindParam(':N_empleado', $N_empleado);
    $stmt->bindParam(':Ape_paterno', $Ape_paterno);
    $stmt->bindParam(':Ape_materno', $Ape_materno);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':folio', $folio);
    $stmt->bindParam(':fecha_solicitud', $fecha_solicitud);
    $stmt->bindParam(':prioridad', $prioridad);
    $stmt->bindParam(':entrada_o_salida', $entrada_o_salida);
    $stmt->bindParam(':fecha_devolucion', $fecha_devolucion);
    $stmt->bindParam(':fines_utilizacion', $fines_utilizacion);
    $stmt->bindParam(':evidencia', $evidencia);
    $stmt->bindParam(':nombre_colab', $nombre_colab);
    $stmt->bindParam(':firma_de_responsable', $firma_de_responsable);
    $stmt->bindParam(':nombre_aut_ho', $nombre_aut_ho);
    $stmt->bindParam(':correo_aut_ho', $correo_aut_ho);
    $stmt->bindParam(':firma_aut_ho', $firma_aut_ho);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el ID de la incidencia recién insertada
    $incidenciaId = $conn->lastInsertId();

    // Insertar en la tabla 'mercancias_servicios'
    if (isset($_POST['tipo_equipo']) && is_array($_POST['tipo_equipo'])) {
        foreach ($_POST['tipo_equipo'] as $index => $partida) {
            $cantidad = $_POST['marca'][$index] ?? '';
            $unidad = $_POST['modelo'][$index] ?? '';
            $descripcion = $_POST['numero_serie'][$index] ?? '';

            $stmt = $conn->prepare("INSERT INTO caracteristicasescolaboradores (
                ESColaboradores_id,
                tipo_equipo,
                marca,
                modelo,
                numero_serie
            ) VALUES (
                :ESColaboradores_id,
                :tipoEquipo,
                :marca,
                :modelo,
                :numeroSerie
            )");
            $stmt->bindParam(':ESColaboradores_id', $incidenciaId); // Asegúrate de usar la variable correcta
            $stmt->bindParam(':tipoEquipo', $partida);
            $stmt->bindParam(':marca', $cantidad);
            $stmt->bindParam(':modelo', $unidad);
            $stmt->bindParam(':numeroSerie', $descripcion);
            $stmt->execute();
        }
    }

    $response['message'] = 'Datos insertados correctamente.';
} catch (PDOException $e) {
    $response['error'] = 'Error al insertar datos: ' . $e->getMessage();
    echo '<pre>';
    echo "Error de PDO: " . $e->getMessage() . "\n";
    echo '</pre>';
} catch (Exception $e) {
    $response['error'] = 'Error al procesar la solicitud: ' . $e->getMessage();
    echo '<pre>';
    echo "Error general: " . $e->getMessage() . "\n";
    echo '</pre>';
}

// Imprimir el mensaje de respuesta para depuración
echo '<pre>';
print_r($_POST);
print_r($_FILES);
echo '</pre>';


// Recoger datos del formulario
$N_colaborador = $_POST['N_colaborador'] ?? '';
$departamento = $_POST['departamento'] ?? '';
$N_empleado = $_POST['N_empleado'] ?? '';
$Ape_paterno = $_POST['Ape_paterno'] ?? '';
$Ape_materno = $_POST['Ape_materno'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$folio = $_POST['folio'] ?? '';
$fecha_solicitud = $_POST['fecha_solicitud'] ?? '';
$prioridad = $_POST['prioridad'] ?? '';
$entrada_o_salida = $_POST['entrada_o_salida'] ?? '';
$fecha_devolucion = $_POST['fecha_devolucion'] ?? '';
$fines_utilizacion = $_POST['fines_utilizacion'] ?? '';
$nombre_colab = $_POST['nombre_colab'] ?? '';
$firma_de_responsable = $_POST['firma_de_responsable'] ?? '';
$aceptacion = ' Me comprometo a asumir la responsabilidad del cuidado y uso, siguiendo las políticas y procedimientos establecidos por la empresa.';
$nombre_aut_ho = $_POST['nombre_aut_ho'] ?? '';
$correo_aut_ho = $_POST['correo_aut_ho'] ?? '';
$firma_aut_ho = $_POST['firma_aut_ho'] ?? '';
$evidencias = $_POST['evidencia'] ?? '';

// Recoger datos de la tabla dinámica
$tabla_dinamica_html = '';
if (isset($_POST['tipo_equipo'])) {
    $tabla_dinamica_html = '
    <style>
        table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    table, th, td {
        border: 1px solid #e0e0e0; /* Light gray border for better visibility */
    }
    th, td {
        padding: 15px;
        text-align: left;
    }
    th {
        background-color: #c62828; /* Dark red background */
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    tr:nth-child(even) {
        background-color: #fbe9e7; /* Light red background for even rows */
    }
    tr:hover {
        background-color: #f8bbd0; /* Slightly darker red for hover effect */
    }
    td {
        border-bottom: 1px solid #e0e0e0;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    caption {
        caption-side: top;
        font-size: 1.5em;
        margin: 10px;
        color: #d32f2f; /* Dark red text color */
    }
    </style>
    <table>
        <thead>
            <tr>
                <th>Tipo de equipo</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Número de serie</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($_POST['tipo_equipo'] as $index => $tipoEquipo) {
        $marca = $_POST['marca'][$index] ?? '';
        $modelo = $_POST['modelo'][$index] ?? '';
        $numeroSerie = $_POST['numero_serie'][$index] ?? '';


        $tabla_dinamica_html .= "
        <tr>
            <td>$tipoEquipo</td>
            <td>$marca</td>
            <td>$modelo</td>
            <td>$numeroSerie</td>
        </tr>";
    }

    $tabla_dinamica_html .= '</tbody></table>';
}

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
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    table, th, td {
        border: 1px solid #e0e0e0; /* Light gray border for better visibility */
    }
    th, td {
        padding: 15px;
        text-align: left;
    }
    th {
        background-color: #c62828; /* Dark red background */
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    tr:nth-child(even) {
        background-color: #fbe9e7; /* Light red background for even rows */
    }
    tr:hover {
        background-color: #f8bbd0; /* Slightly darker red for hover effect */
    }
    td {
        border-bottom: 1px solid #e0e0e0;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    caption {
        caption-side: top;
        font-size: 1.5em;
        margin: 10px;
        color: #d32f2f; /* Dark red text color */
    }
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
            background-color: #ffffff;
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
              <img src="Views/Resources/img/logo.png" alt="Harina de Oriente">
            <h1>Reporte de incidencia informatica</h1>
        </div>
        <div class="details">
            <p><strong>Número de colaborador:</strong> ' . $N_colaborador . '</p>
            <p><strong>Departamento:</strong> ' . $departamento . '</p>
            <p><strong>Nombre del colaborador:</strong> ' . $N_empleado . '</p>
            <p><strong>Apellido paterno:</strong> ' . $Ape_paterno  . '</p>
            <p><strong>Apellido materno:</strong> ' . $Ape_materno  . '</p>
            <p><strong>Correo electrónico:</strong> ' . $correo . '</p>
            <p><strong>Número de teléfono:</strong> ' . $telefono . '</p>
            <p><strong>Folio:</strong> ' . $folio . '</p>
            <p><strong>Fecha de solicitud:</strong> ' . $fecha_solicitud . '</p>
            <p><strong>Propietario del equipo:</strong> ' . $prioridad . '</p>
            <p><strong>¿Entrada o salida?:</strong> ' . $entrada_o_salida . '</p>
            <p><strong>Fecha de devolucion:</strong> ' . $fecha_devolucion . '</p>
            <p><strong>Motivo:</strong> ' . $fines_utilizacion . '</p>
            
            <p><strong>Características:</strong> ' . $tabla_dinamica_html . '</p>
            
            <p><strong>Evidencias:</strong> ' . $evidencias . '</p>
            <p><strong>Nombre del colaborador:</strong> ' . $nombre_colab . '</p>
            <p><strong>Firma de responsable:</strong> ' . $firma_de_responsable . '</p>
            <p><strong>Aceptación de responsabilidad:</strong> ' . $aceptacion . '</p>
            <p><strong>Nombre de quien autoriza:</strong> ' . $nombre_aut_ho . '</p>
            <p><strong>Correo electrónico de quien autoriza:</strong> ' . $correo_aut_ho . '</p>
            <p><strong>Firma de quien autoriza:</strong> ' . $firma_aut_ho . '</p>
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
        table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    table, th, td {
        border: 1px solid #e0e0e0; /* Light gray border for better visibility */
    }
    th, td {
        padding: 15px;
        text-align: left;
    }
    th {
        background-color: #c62828; /* Dark red background */
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    tr:nth-child(even) {
        background-color: #fbe9e7; /* Light red background for even rows */
    }
    tr:hover {
        background-color: #f8bbd0; /* Slightly darker red for hover effect */
    }
    td {
        border-bottom: 1px solid #e0e0e0;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    caption {
        caption-side: top;
        font-size: 1.5em;
        margin: 10px;
        color: #d32f2f; /* Dark red text color */
    }
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
            <p><strong>Número de teléfono:</strong> ' . $telefono . '</p>
            <p><strong>Folio:</strong> ' . $folio . '</p>
            <p><strong>Fecha de solicitud:</strong> ' . $fecha_solicitud . '</p>
            <p><strong>Propietario del equipo:</strong> ' . $prioridad . '</p>
            <p><strong>¿Entrada o salida?:</strong> ' . $entrada_o_salida . '</p>
            <p><strong>Fecha de devolucion:</strong> ' . $fecha_devolucion . '</p>
            <p><strong>Motivo:</strong> ' . $fines_utilizacion . '</p>
            <p><strong>Características:</strong> ' . $tabla_dinamica_html . '</p>
            <p><strong>Evidencias:</strong> ' . $evidencias . '</p>
            <p><strong>Nombre del colaborador:</strong> ' . $nombre_colab . '</p>
            <p><strong>Firma de responsable:</strong> ' . $firma_de_responsable . '</p>
            <p><strong>Aceptación de responsabilidad:</strong> ' . $aceptacion . '</p>
            <p><strong>Nombre de quien autoriza:</strong> ' . $nombre_aut_ho . '</p>
            <p><strong>Correo electrónico de quien autoriza:</strong> ' . $correo_aut_ho . '</p>
            <p><strong>Firma de quien autoriza:</strong> ' . $firma_aut_ho . '</p>
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
