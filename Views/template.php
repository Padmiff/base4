<?php
include "Modules/Header.php";
?>
<main class="main" id="top">
    <div class="container" data-layout="container">
        <script>
            var isFluid = JSON.parse(localStorage.getItem('isFluid'));
            if (isFluid) {
                var container = document.querySelector('[data-layout]');
                container.classList.remove('container');
                container.classList.add('container-fluid');
            }
        </script>
        <?php
        include "Modules/Nav.php";
        ?>
        <div class="content">
            <?php
            include "Modules/Nav_profile.php";
            ?>

            <div class="row g-3 mb-3">

                <?php


                function includePageFromViews($pageName)
                {
                    // Lista de páginas permitidas y sus ubicaciones relativas
                    $allowedPages = [
                        ###### Vistas Admin ######
                        "Management" => "Pages/Management.php",
                        "Empleados" => "Pages/Administrador/Employee/table.Emplyee.php",
                        "EmpleadosBloqueados" => "Pages/Administrador/Employee/table.Emplyeelocked.php",
                        "Proveedores" => "Pages/Administrador/Providers/table.Providers.php",
                        "ProveedoresBloqueados" => "Pages/Administrador/Providers/table.Providerslocked.php",
                        "Clientes" => "Pages/Administrador/Clients/table.Clients.php",
                        "ClientesBloqueados" => "Pages/Administrador/Clients/table.Clientslocked.php",

                        ###### Vista informacion de empleado/Proveedores/Clientes #####
                        "Perfil" => "Pages/Directorios/InfoEmpleados.php",
                        "Contacto" => "Pages/Directorios/InfoProveedor.php",
                        "Cliente" => "Pages/Directorios/InfoCliente.php",

                        ###### Vistas Directorios #####
                        "DirectorioEmpleados" => "Pages/Directorios/Empleados.php",
                        "DirectorioProveedores" => "Pages/Directorios/Proveedores.php",
                        "DirectorioClientes" => "Pages/Directorios/Clientes.php",

                        ##### Gestionar Archivos #####
                        "Archivos" => "Pages/Archivos/Archivos.php",

                        ###### Formularios ######
                        "CatalogoColaboradores" => "Pages/Forms/CatalogoColaboradores.php",
                        "SolicitudMercancia" => "Pages/Forms/SolicitudMercancia.php",
                        "ESequipoVisitantes" => "Pages/Forms/ESVisitantes.php",
                        "ESequipoColaboradores" => "Pages/Forms/ESColaboradores.php",


                    ];

                    // Verificar si la página solicitada está permitida
                    if (array_key_exists($pageName, $allowedPages)) {
                        // Construir la ruta completa al archivo de la página
                        $pagePath = "Views/" . $allowedPages[$pageName];

                        // Incluir la página si existe
                        if (file_exists($pagePath)) {
                            include $pagePath;
                        } else {
                            echo "</br></br>Error: La página solicitada no existe.";
                        }
                    } else {
                        echo "</br></br>Error: Página no permitida.";
                    }
                }

                // Uso de la función para incluir la página solicitada
                if (isset($_GET["Pages"])) {
                    $requestedPage = $_GET["Pages"];
                    includePageFromViews($requestedPage);
                }
                ?>

                <?php
                include "Modules/Footer.php";
                ?>
            </div>
        </div>
</main>
<?php
include "Modules/Btn_config.php";
?>