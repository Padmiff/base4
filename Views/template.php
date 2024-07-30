<?php
include "Modules/Auth.php";
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
        // Incluir el archivo de navegación según el rol del usuario
        if (isset($_SESSION['user_rol'])) {
            $user_role = $_SESSION['user_rol'];
            switch ($user_role) {
                case 'administrador':
                case 'moderador':
                    include "Modules/Nav.php"; // Incluir navegación para administrador y moderador
                    break;
                case 'colaborador':
                    include "Modules/Nav_colab.php"; // Incluir navegación para colaborador
                    break;
                case 'proveedor':
                    include "Modules/Nav_proveedor.php"; // Incluir navegación para proveedor
                    break;
                case 'cliente':
                    include "Modules/Nav_cliente.php"; // Incluir navegación para cliente
                    break;

                    // Puedes agregar más casos según los roles que tengas definidos
                default:
                    include "Modules/Nav.php"; // Por defecto, incluir navegación de perfil
                    break;
            }
        } else {
            // Si no hay rol definido, incluir navegación de perfil por defecto
            include "Modules/Nav.php";
        }
        ?>

        <div class="content">
            <?php
            include "Modules/Nav_profile.php";
            ?>

            <div class="row g-3 mb-3">
                <?php
                function includePageFromViews($pageName, $userRole)
                {
                    // Lista de páginas permitidas y sus ubicaciones relativas
                    $allowedPages = [
                        'administrador' => [
                            "Management" => "Pages/Management.php",
                            "Empleados" => "Pages/Administrador/Employee/table.Emplyee.php",
                            "EmpleadosBloqueados" => "Pages/Administrador/Employee/table.Emplyeelocked.php",
                            "Proveedores" => "Pages/Administrador/Providers/table.Providers.php",
                            "ProveedoresBloqueados" => "Pages/Administrador/Providers/table.Providerslocked.php",
                            "Clientes" => "Pages/Administrador/Clients/table.Clients.php",
                            "ClientesBloqueados" => "Pages/Administrador/Clients/table.Clientslocked.php",
                            "ContactosClientes" => "Pages/Administrador/Clients/Contacts.php",
                            "ContactosProveedores" => "Pages/Administrador/Providers/Contacts.php",
                            "Graficas" => "Pages/Graficas/grafi.php",
                            "Perfil" => "Pages/Directorios/InfoEmpleados.php",
                            "Contacto" => "Pages/Directorios/InfoProveedor.php",
                            "Cliente" => "Pages/Directorios/InfoCliente.php",
                            "DirectorioEmpleados" => "Pages/Directorios/Empleados.php",
                            "DirectorioProveedores" => "Pages/Directorios/Proveedores.php",
                            "DirectorioClientes" => "Pages/Directorios/Clientes.php",
                            "Archivos" => "Pages/Archivos/Archivos.php",
                            "CatalogoColaboradores" => "Pages/Forms/CatalogoColaboradores.php",
                            "SolicitudMercancia" => "Pages/Forms/SolicitudMercancia.php",
                            "ESequipoVisitantes" => "Pages/Forms/ESVisitantes.php",
                            "ESequipoColaboradores" => "Pages/Forms/ESColaboradores.php",
                            "RepIndicencias" => "Pages/Forms/RepIndicencias.php",
                        ],
                        'moderador' => [
                            "Management" => "Pages/Management.php",
                            "Empleados" => "Pages/Administrador/Employee/table.Emplyee.php",
                            "EmpleadosBloqueados" => "Pages/Administrador/Employee/table.Emplyeelocked.php",
                            "Proveedores" => "Pages/Administrador/Providers/table.Providers.php",
                            "ProveedoresBloqueados" => "Pages/Administrador/Providers/table.Providerslocked.php",
                            "Clientes" => "Pages/Administrador/Clients/table.Clients.php",
                            "ClientesBloqueados" => "Pages/Administrador/Clients/table.Clientslocked.php",
                            "ContactosClientes" => "Pages/Administrador/Clients/Contacts.php",
                            "Graficas" => "Pages/Graficas/grafi.php",
                            "Perfil" => "Pages/Directorios/InfoEmpleados.php",
                            "Contacto" => "Pages/Directorios/InfoProveedor.php",
                            "Cliente" => "Pages/Directorios/InfoCliente.php",
                            "DirectorioEmpleados" => "Pages/Directorios/Empleados.php",
                            "DirectorioProveedores" => "Pages/Directorios/Proveedores.php",
                            "DirectorioClientes" => "Pages/Directorios/Clientes.php",
                            "Archivos" => "Pages/Archivos/Archivos.php",
                            "CatalogoColaboradores" => "Pages/Forms/CatalogoColaboradores.php",
                            "SolicitudMercancia" => "Pages/Forms/SolicitudMercancia.php",
                            "ESequipoVisitantes" => "Pages/Forms/ESVisitantes.php",
                            "ESequipoColaboradores" => "Pages/Forms/ESColaboradores.php",
                            "RepIndicencias" => "Pages/Forms/RepIndicencias.php",
                        ],
                        'colaborador' => [
                            "Management" => "Pages/Management.php",
                            "Archivos" => "Pages/Archivos/Archivos.php",
                            "SolicitudMercancia" => "Pages/Forms/SolicitudMercancia.php",
                            "ESequipoVisitantes" => "Pages/Forms/ESVisitantes.php",
                            "ESequipoColaboradores" => "Pages/Forms/ESColaboradores.php",
                            "RepIndicencias" => "Pages/Forms/RepIndicencias.php",
                        ],
                        'proveedor' => [
                            "Management" => "Pages/Management.php",
                            "Graficas" => "Pages/Graficas/grafi.php",
                            "Archivos" => "Pages/Archivos/Archivos.php",
                            "CatalogoColaboradores" => "Pages/Forms/CatalogoColaboradores.php",
                            "SolicitudMercancia" => "Pages/Forms/SolicitudMercancia.php",
                            "ESequipoVisitantes" => "Pages/Forms/ESVisitantes.php",
                            "ESequipoColaboradores" => "Pages/Forms/ESColaboradores.php",
                            "RepIndicencias" => "Pages/Forms/RepIndicencias.php",
                        ],
                        'cliente' => [
                            "Management" => "Pages/Management.php",
                            "Graficas" => "Pages/Graficas/grafi.php",
                            "Archivos" => "Pages/Archivos/Archivos.php",
                            "CatalogoColaboradores" => "Pages/Forms/CatalogoColaboradores.php",
                            "SolicitudMercancia" => "Pages/Forms/SolicitudMercancia.php",
                            "ESequipoVisitantes" => "Pages/Forms/ESVisitantes.php",
                            "ESequipoColaboradores" => "Pages/Forms/ESColaboradores.php",
                            "RepIndicencias" => "Pages/Forms/RepIndicencias.php",
                        ]
                    ];

                    // Verificar si la página solicitada está permitida para el rol del usuario
                    if (array_key_exists($userRole, $allowedPages) && array_key_exists($pageName, $allowedPages[$userRole])) {
                        // Construir la ruta completa al archivo de la página
                        $pagePath = "Views/" . $allowedPages[$userRole][$pageName];

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
                if (isset($_GET["Pages"]) && isset($_SESSION['user_rol'])) {
                    $requestedPage = $_GET["Pages"];
                    $userRole = $_SESSION['user_rol'];
                    includePageFromViews($requestedPage, $userRole);
                } else {
                    echo "</br></br>Error: No se pudo determinar la página o el rol del usuario.";
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