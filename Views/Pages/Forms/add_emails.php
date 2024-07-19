<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Correos Adicionales</title>
</head>

<body>

    <div class="container mt-4">
        <h1>Agregar Correos Adicionales</h1>
        <form id="extraEmailsForm" onsubmit="saveEmails(event)">
            <div id="emailsContainer">
                <!-- Los campos de correo se agregarán aquí -->
            </div>
            <button type="button" class="btn btn-secondary" onclick="addEmailField()">Agregar otro correo</button>
            <button type="submit" class="btn btn-primary">Guardar correos</button>
        </form>
    </div>
    <script src="Views/Resources/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cargar correos guardados al cargar la página
            loadEmails();
        });
    </script>

</body>

</html>